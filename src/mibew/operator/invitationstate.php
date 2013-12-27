<?php
/*
 * Copyright 2005-2013 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once(dirname(dirname(__FILE__)).'/libs/init.php');
require_once(MIBEW_FS_ROOT.'/libs/invitation.php');
require_once(MIBEW_FS_ROOT.'/libs/operator.php');
require_once(MIBEW_FS_ROOT.'/libs/classes/mibew_api.php');
require_once(MIBEW_FS_ROOT.'/libs/classes/mibew_api_interaction.php');
require_once(MIBEW_FS_ROOT.'/libs/classes/mibew_api_invite_interaction.php');
require_once(MIBEW_FS_ROOT.'/libs/classes/mibew_api_execution_context.php');

$processor = \Mibew\RequestProcessor\InviteProcessor::getInstance();
$processor->receiveRequest($_POST['data']);

?>