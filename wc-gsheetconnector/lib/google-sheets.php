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
					// update_option('gs_verify', 'Something went wrong! It looks you have not given the permission of Google Drive and Google Sheets from your google account.Please Deactivate Auth and Re-Authenticate again with the permissions.');
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

	public function add_row($data_value, $gscwoo_operation, $gscwoo_sheetname, $gscwoo_order_id)
	{
		try {
			$client = self::getInstance();
			$service = new Google_Service_Sheets($client);
			$spreadsheetId = $this->getSpreadsheetId();
			$work_sheets = $service->spreadsheets->get($spreadsheetId);
			/* new Update  START */
			if (!empty($work_sheets) && !empty($data_value)) {
				foreach ($work_sheets as $sheet) {
					$properties = $sheet->getProperties();
					$p_title = $properties->getSheetId();
					$w_title = $this->getWorkTabId();
					if ($p_title == $w_title) {
						$w_title = $properties->getTitle();
						$worksheetCell = $service->spreadsheets_values->get($spreadsheetId, $w_title . "!1:1");
						$insert_data = array();
						if (isset($worksheetCell->values[0])) {
							$insert_data_index = 0;
							foreach ($worksheetCell->values[0] as $k => $name) {
								if ($insert_data_index == 0) {
									if (isset($data_value[$name]) && $data_value[$name] != '') {
										$insert_data[] = $data_value[$name];
									} else {
										$insert_data[] = '';
									}
								} else {
									if (isset($data_value[$name]) && $data_value[$name] != '') {
										$insert_data[] = $data_value[$name];
									} else {
										$insert_data[] = '';
									}
								}
								$insert_data_index++;
							}
						}
						$tab_name = $w_title;
						$full_range = $tab_name . "!A1:Z";
						$response = $service->spreadsheets_values->get($spreadsheetId, $full_range);
						$get_values = $response->getValues();

						if ($get_values) {
							$row = count($get_values) + 1;
						} else {
							$row = 1;
						}
						/* Get the range of sheet - START */
						if ($gscwoo_operation == "update") {
							$gscwoo_sheet = "'" . $gscwoo_sheetname . "'!A:A";
							$gscwoo_allentry = $service->spreadsheets_values->get($spreadsheetId, $gscwoo_sheet);
							$gscwoo_data = $gscwoo_allentry->getValues();
							$counter = 1;
							foreach ($gscwoo_data as $key => $value) {
								if ($value[0] == $gscwoo_order_id) {
									$gscwoo_num = $counter;
									break;
								}
								$counter++;
							}
							$range = $tab_name . '!A' . $gscwoo_num;
						}
						/* Get the range of sheet - END */

						/* Add and Delete Row - START */
						if ($gscwoo_operation == "add_delete") {
							$gscwoo_sheet = "'" . $gscwoo_sheetname . "'!A:A";
							$gscwoo_allentry = $service->spreadsheets_values->get($spreadsheetId, $gscwoo_sheet);
							$gscwoo_data = $gscwoo_allentry->getValues();
							$counter = 1;
							foreach ($gscwoo_data as $key => $value) {
								if ($value[0] == $gscwoo_order_id) {
									$gscwoo_num = $counter;
									break;
								}
								$counter++;
							}
							$range = $tab_name . '!A' . $gscwoo_num;
						}
						/* Add and Delete Row - END */

						if ($gscwoo_operation == "insert")
							$range = $tab_name . "!A" . $row . ":Z";

						$range_new = $w_title;
						// Create the value range Object
						$valueRange = new Google_Service_Sheets_ValueRange();
						// You need to specify the values you insert
						$valueRange->setValues(["values" => $insert_data]);
						// Add two values
						// Then you need to add some configuration
						$conf = ["valueInputOption" => "USER_ENTERED", "insertDataOption" => "INSERT_ROWS"];
						$conf = ["valueInputOption" => "USER_ENTERED"];
						// append the spreadsheet
						if ($gscwoo_operation == "update")
							$result = $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $conf);

						if ($gscwoo_operation == "add_delete") {
							// Code Pending From - 09-06-2021
							// echo "=============== add update =================";
							// $conf = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
							//       'requests' => array(
							//          'deleteDimension' => array(
							//             'range' => array(
							//               'dimension'  => 'ROWS',
							//               'sheetId'    => $spreadsheetId,
							//               'startIndex'    => $gscwoo_num,
							//               'endIndex'   => $gscwoo_num+1
							//             )
							//          )
							//       )));
							//    $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $range, $valueRange, $conf);
							// Code Pending From - 09-06-2021
						}


						if ($gscwoo_operation == "insert")
							$result = $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $conf);

					}
				}
			}
		} catch (Exception $e) {
			
			return null;
			exit();
		}
	}

	public function add_multiple_row($data)
	{
		try {
			$client = self::getInstance();
			$service = new Google_Service_Sheets($client);
			$spreadsheetId = $this->getSpreadsheetId();
			$work_sheets = $service->spreadsheets->get($spreadsheetId);

			if (!empty($work_sheets) && !empty($data)) {
				foreach ($work_sheets as $sheet) {
					$properties = $sheet->getProperties();
					$sheet_id = $properties->getSheetId();

					$worksheet_id = $this->getWorkTabId();

					if ($sheet_id == $worksheet_id) {
						$worksheet_id = $properties->getTitle();
						$worksheetCell = $service->spreadsheets_values->get($spreadsheetId, $worksheet_id . "!1:1");
						$insert_data = array();
						$final_data = array();
						if (isset($worksheetCell->values[0])) {
							foreach ($data as $key => $value) {
								foreach ($worksheetCell->values[0] as $k => $name) {
									if (isset($value[$name]) && $value[$name] != '') {
										$insert_data[] = $value[$name];
									} else {
										$insert_data[] = '';
									}
								}
								$final_data[] = $insert_data;
								unset($insert_data);
							}
						}

						$range_new = $worksheet_id;

						$sheet_values = $final_data;

						if (!empty($sheet_values)) {
							$requestBody = new Google_Service_Sheets_ValueRange([
								'values' => $sheet_values
							]);

							$params = [
								'valueInputOption' => 'USER_ENTERED'
							];
							$response = $service->spreadsheets_values->append($spreadsheetId, $range_new, $requestBody, $params);
						}
					}
				}
			}
		} catch (Exception $e) {
			
			return null;
			exit();
		}
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
