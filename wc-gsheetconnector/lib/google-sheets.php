<?php

if (!defined('ABSPATH'))
	exit;

include_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

class GSCWOO_googlesheet
{

	private $token;
	private $spreadsheet;
	private $worksheet;


	private static $instance;

	public function __construct()
	{

	}

	public static function setInstance(Google_Client $instance = null)
	{
		self::$instance = $instance;
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			throw new LogicException("Invalid Client");
		}

		return self::$instance;
	}

	//constructed on call
	public static function preauth($access_code)
	{
		// Fetch API creds
		if (is_multisite()) {
			// Fetch API creds
			$api_creds = get_site_option('wcgsc_api_free_creds');
		} else {
			// Fetch API creds
			$api_creds = get_option('wcgsc_api_free_creds');
		}
		$newClientSecret = get_option('is_new_client_secret_wcgsc');
		$clientId = ($newClientSecret == 1) ? $api_creds['client_id_web'] : $api_creds['client_id_desk'];
		$clientSecret = ($newClientSecret == 1) ? $api_creds['client_secret_web'] : $api_creds['client_secret_desk'];

		$client = new Google_Client();
		$client->setClientId($clientId);
		$client->setClientSecret($clientSecret);
		$client->setRedirectUri('https://oauth.gsheetconnector.com');
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
		$client->setAccessType('offline');
		$client->fetchAccessTokenWithAuthCode($access_code);
		$tokenData = $client->getAccessToken();

		GSCWOO_googlesheet::updateToken($tokenData);
	}

	public static function updateToken($tokenData)
	{
		$tokenData['expire'] = time() + intval($tokenData['expires_in']);
		try {
			//$tokenJson = json_encode($tokenData);
			//update_option('gfgs_token', $tokenJson);
			//resolved - google sheet permission issues - START
			if (isset($tokenData['scope'])) {
				$permission = explode(" ", $tokenData['scope']);
				if ((in_array("https://www.googleapis.com/auth/drive.metadata.readonly", $permission)) && (in_array("https://www.googleapis.com/auth/spreadsheets", $permission))) {
					update_option('wcgsc_verify', 'valid');
				} else {
					update_option('wcgsc_verify', 'invalid-auth');
				}
			}
			$tokenJson = json_encode($tokenData);
			update_option('wcgsc_token', $tokenJson);
			//resolved - google sheet permission issues - END

		} catch (Exception $e) {
			
		}
	}

	public function auth()
	{
		$tokenData = json_decode(get_option('wcgsc_token'), true);
		if (!isset($tokenData['refresh_token']) || empty($tokenData['refresh_token'])) {
			throw new LogicException("Auth, Invalid OAuth2 access token");
			exit();
		}

		try {
			// Fetch API creds
			if (is_multisite()) {
				// Fetch API creds
				$api_creds = get_site_option('wcgsc_api_free_creds');
			} else {
				// Fetch API creds
				$api_creds = get_option('wcgsc_api_free_creds');
			}
			$newClientSecret = get_option('is_new_client_secret_wcgsc');
			$clientId = ($newClientSecret == 1) ? $api_creds['client_id_web'] : $api_creds['client_id_desk'];
			$clientSecret = ($newClientSecret == 1) ? $api_creds['client_secret_web'] : $api_creds['client_secret_desk'];

			$client = new Google_Client();
			$client->setClientId($clientId);
			$client->setClientSecret($clientSecret);

			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
			$client->refreshToken($tokenData['refresh_token']);
			$client->setAccessType('offline');
			GSCWOO_googlesheet::updateToken($tokenData);

			self::setInstance($client);
		} catch (Exception $e) {
			
			exit();
		}
	}

	public function setSpreadsheetId($id)
	{
		$this->spreadsheet = $id;
	}

	public function getSpreadsheetId()
	{

		return $this->spreadsheet;
	}

	public function setWorkTabId($id)
	{
		$this->worksheet = $id;
	}

	public function getWorkTabId()
	{
		return $this->worksheet;
	}

	//get all the spreadsheets
	public function get_spreadsheets()
	{
		$all_sheets = array();
		try {
			$client = self::getInstance();

			$service = new Google_Service_Drive($client);

			$optParams = array(
				'q' => "mimeType='application/vnd.google-apps.spreadsheet'"
			);
			$results = $service->files->listFiles($optParams);
			foreach ($results->files as $spreadsheet) {
				if (isset($spreadsheet['kind']) && $spreadsheet['kind'] == 'drive#file') {
					$all_sheets[] = array(
						'id' => $spreadsheet['id'],
						'title' => $spreadsheet['name'],
					);
				}
			}
		} catch (Exception $e) {
			
			return null;
			exit();
		}
		return $all_sheets;
	}

	//get worksheets title
	public function get_worktabs($spreadsheet_id)
	{
		$work_tabs_list = array();
		try {
			$client = self::getInstance();
			$service = new Google_Service_Sheets($client);
			$work_sheets = $service->spreadsheets->get($spreadsheet_id);


			foreach ($work_sheets as $sheet) {
				$properties = $sheet->getProperties();
				$work_tabs_list[] = array(
					'id' => $properties->getSheetId(),
					'title' => $properties->getTitle(),
				);
			}
		} catch (Exception $e) {
			
			return null;
			exit();
		}

		return $work_tabs_list;
	}


	public function perform_sheet_tab_updates($spreadsheet_id, $request_array)
	{
		$gscwoo_client = self::getInstance();
		$gscwoo_service = new Google_Service_Sheets($gscwoo_client);
		$update_request = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array('requests' => $request_array));
		$gscwoo_response = $gscwoo_service->spreadsheets->batchUpdate($spreadsheet_id, $update_request);
	}


	public function getTabId($selected_sheet_id, $gscwoo_sheetname)
	{
		$tabsArr = $this->get_worktabs($selected_sheet_id);
		foreach ($tabsArr as $key => $value) {
			if ($value["title"] == $gscwoo_sheetname)
				$tabId = $value["id"];
		}
		return $tabId;
	}


	/**************************************************************
	 ** FUNCTIONS BY RASHID **
	 **************************************************************/

	public function get_sheet_tabs($spreadsheet_id)
	{
		$tabs = $this->get_worktabs($spreadsheet_id);
		$tabs = wp_list_pluck($tabs, "title", "id");
		return $tabs;
	}

	public function get_sheet_name($spreadsheet_id, $tab_id)
	{

		$all_sheet_data = get_option('wcgsc_sheetId');

		$tab_name = "";
		foreach ($all_sheet_data as $spreadsheet) {

			if ($spreadsheet['id'] == $spreadsheet_id) {
				$tabs = $spreadsheet['tabId'];

				foreach ($tabs as $name => $id) {
					if ($id == $tab_id) {
						$tab_name = $name;
					}
				}
			}
		}

		$tab_name = apply_filters("gcwoo_filter_tab_name", $tab_name, $spreadsheet_id, $tab_id);
		return $tab_name;
	}

	public function get_spreadsheet_name($spreadsheet_id)
	{

		$all_sheet_data = get_option('wcgsc_sheetId');

		$spreadsheetName = "";
		foreach ($all_sheet_data as $spreadsheet_name => $spreadsheet) {

			if ($spreadsheet['id'] == $spreadsheet_id) {
				$spreadsheetName = $spreadsheet_name;
			}
		}

		$spreadsheetName = apply_filters("gcwoo_filter_spreasheet_name", $spreadsheetName, $spreadsheet_id);

		return $spreadsheetName;
	}

	

	public function remove_row_by_order_id($spreadsheet_id, $tab_name, $order_id, $order_id_index)
	{

		$client = self::getInstance();

		if (!$client) {
			return false;
		}

		try {
			$tab_id = $this->getTabId($spreadsheet_id, $tab_name);
			$service = new Google_Service_Sheets($client);
			$full_range = $tab_name . "!A1:Z";
			$response = $service->spreadsheets_values->get($spreadsheet_id, $full_range);
			$get_values = $response->getValues();

			$order_ids = wp_list_pluck($get_values, $order_id_index);

			

			$index = array_search($order_id, $order_ids);

			if ($index != false) {

				$conf = array(
					'requests' => array(
						'deleteDimension' => array(
							'range' => array(
								'dimension' => 'ROWS',
								'sheetId' => $tab_id,
								'startIndex' => $index,
								'endIndex' => $index + 1
							)
						)
					)
				);

				$conf = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest($conf);

				$result = $service->spreadsheets->batchUpdate($spreadsheet_id, $conf);
			}

		} catch (Exception $e) {
			
			return false;
		}
	}

	public function update_row_by_order_id($spreadsheet_id, $tab_name, $row_data, $order_id, $order_id_index)
	{

		$client = self::getInstance();

		if (!$client) {
			return false;
		}

		try {
			$tab_id = $this->getTabId($spreadsheet_id, $tab_name);
			$service = new Google_Service_Sheets($client);
			$full_range = $tab_name . "!A1:Z";
			$response = $service->spreadsheets_values->get($spreadsheet_id, $full_range);
			$get_values = $response->getValues();

			$order_ids = wp_list_pluck($get_values, $order_id_index);
			$row = array_search($order_id, $order_ids);

			foreach ($row_data as &$data) {
				$data = str_replace("{row}", $row, $data);
			}

			if ($row === false) {
				if ($get_values) {
					$row = count($get_values) + 1;
				} else {
					$row = 1;
				}

				$range = $tab_name . "!A" . $row . ":Z";
				$valueRange = new Google_Service_Sheets_ValueRange();
				$valueRange->setValues(["values" => $row_data]);
				$conf = ["valueInputOption" => "USER_ENTERED", "insertDataOption" => "INSERT_ROWS"];
				$result = $service->spreadsheets_values->append($spreadsheet_id, $range, $valueRange, $conf);
			} else {
				$row = $row + 1;
				$range = $tab_name . "!A" . $row . ":" . $row;
				$valueRange = new Google_Service_Sheets_ValueRange();
				$valueRange->setValues(["values" => $row_data]);
				$conf = ["valueInputOption" => "USER_ENTERED"];
				$result = $service->spreadsheets_values->update($spreadsheet_id, $range, $valueRange, $conf);
			}

			

		} catch (Exception $e) {
			
			return false;
		}
	}

	public function add_row_to_sheet($spreadsheet_id, $tab_name, $row_data, $order, $is_header = false)
	{

		if (!$row_data) {
			return;
		}

		ksort($row_data);

		try {
			$client = self::getInstance();

			if (!$client) {
				return false;
			}

			$service = new Google_Service_Sheets($client);


			$full_range = $tab_name . "!A1:Z";
			$response = $service->spreadsheets_values->get($spreadsheet_id, $full_range);
			$get_values = $response->getValues();

			if ($get_values) {
				$row = count($get_values) + 1;
			} else {
				$row = 1;
			}

			foreach ($row_data as &$data) {
				$data = str_replace("{row}", $row, $data);
			}


			if ($is_header) {
				$range = $tab_name . '!1:1';
				$valueRange = new Google_Service_Sheets_ValueRange();
				$valueRange->setValues(["values" => $row_data]);
				$conf = ["valueInputOption" => "RAW"];
				$result = $service->spreadsheets_values->update($spreadsheet_id, $range, $valueRange, $conf);
				do_action("gcwoo_header_updated", $row_data);
			} else {
				$range = $tab_name . "!A" . $row . ":Z";
				$valueRange = new Google_Service_Sheets_ValueRange();
				$valueRange->setValues(["values" => $row_data]);
				$conf = ["valueInputOption" => "USER_ENTERED", "insertDataOption" => "INSERT_ROWS"];
				$result = $service->spreadsheets_values->append($spreadsheet_id, $range, $valueRange, $conf);
				do_action("gcwoo_entry_added", $row_data, $order);
			}
			return true;
		} catch (Exception $e) {
			
			return false;
		}
	}

	public function get_header_row($spreadsheet_id, $tab_id)
	{

		$header_cells = array();
		try {

			$client = $this->getInstance();

			if (!$client) {
				return false;
			}

			$service = new Google_Service_Sheets($client);

			$work_sheets = $service->spreadsheets->get($spreadsheet_id);

			if ($work_sheets) {

				foreach ($work_sheets as $sheet) {

					$properties = $sheet->getProperties();
					$work_sheet_id = $properties->getTitle();

					if ($work_sheet_id == $tab_id) {

						$tab_title = $properties->getTitle();
						$header_row = $service->spreadsheets_values->get($spreadsheet_id, $tab_title . "!1:1");

						$header_row_values = $header_row->getValues();

						if (isset($header_row_values[0]) && $header_row_values[0]) {
							$header_cells = $header_row_values[0];
						}
					}
				}
			}
		} catch (Exception $e) {
			$header_cells = array();
			
		}

		$header_cells = apply_filters("gcwoo_fetched_header_cells", $header_cells, $spreadsheet_id, $tab_id);

		return $header_cells;
	}

	public function gsheet_get_google_account()
	{

		try {
			$client = $this->getInstance();

			if (!$client) {
				return false;
			}

			$service = new Google_Service_Oauth2($client);
			$user = $service->userinfo->get();
		} catch (Exception $e) {
			
			return false;
		}

		return $user;
	}

	public function gsheet_get_google_account_email()
	{
		$google_account = $this->gsheet_get_google_account();

		if ($google_account) {
			return $google_account->email;
		} else {
			return "";
		}
	}

	/** 
	 * GFGSC_googlesheet::gsheet_print_google_account_email
	 * Get Google Account Email
	 * @since 3.1 
	 * @retun string $google_account
	 **/
	public function gsheet_print_google_account_email()
	{

		try {
		        $google_sheet = new GSCWOO_googlesheet();
				$google_sheet->auth();
				$email = $google_sheet->gsheet_get_google_account_email();
				update_option("wcgsc_email_account", $email);
				return $email;
		    } catch (Exception $e) {
			
			return false;
		}
	}

}
