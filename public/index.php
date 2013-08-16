<?php

/**
 * Demonstration of the Ticket Evolution PHP Library for use with Zend Framework 1
 *
 * LICENSE
 *
 * This source file is subject to the BSD 3-Clause License that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/TeamOneTickets/ticket-evolution-api-demo/blob/master/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@teamonetickets.com so we can send you a copy immediately.
 *
 * @author      J Cobb <j@teamonetickets.com>
 * @copyright   Copyright (c) 2013 Team One Tickets & Sports Tours, Inc. (http://www.teamonetickets.com)
 * @license     https://github.com/TeamOneTickets/ticket-evolution-api-demo/blob/master/LICENSE.txt     BSD 3-Clause License
 */


/**
 * Get the configuration
 * Be sure to copy config.sample.php to config.php and enter your own information.
 */
@(include_once '../application/config.php')
    OR die ('You need to copy /application/config.sample.php to /application/config.php and enter your own API credentials');


/**
 * Use Composer’s autoloader.
 */
require_once '../vendor/autoload.php';


session_start();


$environments = array(
    'sandbox',
    'staging',
    'production',
);

$options = array(
    'apiToken',
    'secretKey',
    'buyerId',
);

foreach ($environments as $environment) {
    foreach ($options as $option) {
        if (!empty(${$environment}[$option]) && empty($_SESSION[$environment][$option])) {
            $_SESSION[$environment][$option] = ${$environment}[$option];
        }
    }
}



/**
 * If the form has been submitted filter & validate the input for safety.
 * This is just good practice.
 *
 * Because of the HUGE number of possibilities of specific method parameters
 * we are only filtering a few standard ones as an example.
 */
if (isset($_REQUEST['libraryMethod'])) {
    $filters = array(
        '*' => array(
            'StringTrim',
            'StripTags',
            'StripNewlines',
        )
    );
    $validators = array(
        'libraryMethod' => array(
            'Alpha',
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'environment' => array(
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'apiToken' => array(
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'apiVersion' => array(
            'Digits',
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'secretKey' => array(
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'buyerId' => array(
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
    );

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = new \Zend_Filter_Input($filters, $validators, $_POST);
    } else {
        $input = new \Zend_Filter_Input($filters, $validators, $_GET);
    }
    //var_dump($_GET);

    $cfg['params']['apiToken'] = $input->apiToken;
    $cfg['params']['secretKey'] = $input->secretKey;
    $cfg['params']['buyerId'] = $input->buyerId;

    switch ($input->environment) {
        case 'production':
            $cfg['params']['baseUri'] = 'https://api.ticketevolution.com';
            break;

        case 'staging':
            $cfg['params']['baseUri'] = 'https://api.staging.ticketevolution.com';
            break;

        case 'sandbox':
        default:
            $cfg['params']['baseUri'] = 'https://api.sandbox.ticketevolution.com';
    }
    $cfg['params']['apiVersion'] = $input->apiVersion;

    foreach ($options as $option) {
        $_SESSION[$input->environment][$option] = $cfg['params'][$option];
    }

    /**
     * You can initialize the TicketEvolution class with either a \Zend_Config object
     * or with the above $cfg array.
     *
     * Zend_Config method
     * $config = new \Zend_Config($cfg);
     * $tevo = new \TicketEvolution\Webservice($cfg->params);
     *
     * Array method
     * $tevo = new \TicketEvolution\Webservice($cfg['params']);
     */

    // We'll use the Zend_Config method here
    $config = new \Zend_Config($cfg);

    $tevo = new \TicketEvolution\Webservice($config->params);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Demonstration of the Ticket Evolution PHP Library for use with Zend Framework 1</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Demonstration of the Ticket Evolution PHP Library for use with Zend Framework 1">
        <meta name="author" content="J Cobb <j+ticketevolution@teamonetickets.com>">

        <!-- Stylesheets -->
        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/bootstrap-tagmanager.css" rel="stylesheet">

        <link rel="shortcut icon" href="/favicon.ico">
    </head>
    <body>
        <div>
            <img class="banner sandbox" src="images/sandbox-banner.png" alt="sandbox" />
            <img class="banner staging" src="images/staging-banner.png" alt="staging" />
            <img class="banner production" src="images/production-banner.png" alt="production" />
        </div>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="/">Ticket Evolution PHP Library Demo</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Demo <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="https://github.com/TeamOneTickets/ticket-evolution-php-library-demo">GitHub <i class="icon-white icon-github"></i></a></li>
                                    <li><a href="https://github.com/TeamOneTickets/ticket-evolution-php-library-demo/issues" target="_blank">Issues</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">PHP Library <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="https://github.com/ticketevolution/ticketevolution-php">GitHub</a></li>
                                    <li><a href="https://github.com/ticketevolution/ticketevolution-php/issues" target="_blank">Issues</a></li>
                                    <li><a href="https://github.com/ticketevolution/ticketevolution-php/wiki" target="_blank">Wiki</a></li>
                                </ul>
                            </li>
                            <li><a href="http://www.ticketevolution.com/products/api-affiliates-and-partners/" target="_blank">Ticket Evolution <i class="icon-white icon-share-alt"></i></a></li>
                            <li><a href="http://developer.ticketevolution.com/" target="_blank">API Documentation <i class="icon-white icon-share-alt"></i></a></li>
                            <li><a href="http://www.teamonetickets.com/" target="_blank">Team One Tickets <i class="icon-white icon-share-alt"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container main">
            <div class="page-header">
                <h1>Demonstration of the Ticket Evolution PHP Library <small>for use with Zend Framework 1</small></h1>
            </div>
		    <p>This is a quick demo of the Ticket Evolution PHP Library for use with <a href="http://framework.zend.com/">Zend Framework 1</a> which is used to access the <a href="http://developer.ticketevolution.com/overview">Ticket Evolution Web Services API</a>. Zend Framework is an easy-to-use PHP framework that can be used in whole or in parts regardless of whether you program in MVC or procedural style. Simply make sure that the Zend Framework <code>/library</code> folder is in your PHP <code>include_path</code>.</p>
		    <p>All of the <code>list*()</code> methods will return a <code>\TicketEvolution\Webservice\ResultSet</code> object with can be easily iterated using simple loops. If you prefer PHP’s <a href="http://www.php.net/manual/en/spl.iterators.php">built-in SPL iterators</a> you will be hapy to know that <code>\TicketEvolution\Webservice_ResultSet</code> implements <a href="http://www.php.net/manual/en/class.seekableiterator.php">SeekableIterator</a>.</p>

		    <?php
		        if (isset($input)) {
                    echo '<h2>Current configuration code</h2>' . PHP_EOL
                       . '<pre>' . PHP_EOL
                       . '/**' . PHP_EOL
                       . ' * Setup configuration' . PHP_EOL
                       . ' */' . PHP_EOL
                       . '$cfg[\'params\'][\'apiToken\'] = (string) \'' . $cfg['params']['apiToken'] . '\';' . PHP_EOL
                       . '$cfg[\'params\'][\'secretKey\'] = (string) \'' . $cfg['params']['secretKey'] . '\';' . PHP_EOL
                       . '$cfg[\'params\'][\'apiVersion\'] = (string) \'' . $cfg['params']['apiVersion'] . '\';' . PHP_EOL
                       . '$cfg[\'params\'][\'buyerId\'] = \'' . $cfg['params']['buyerId'] . '\';' . PHP_EOL
                       . '$cfg[\'params\'][\'baseUri\'] = (string) \'' . $cfg['params']['baseUri'] . '\';' . PHP_EOL
                       . PHP_EOL
                       . '/**' . PHP_EOL
                       . ' * Create a \Zend_Config object to pass to \TicketEvolution\Webservice' . PHP_EOL
                       . ' */' . PHP_EOL
                       . '$config = new \Zend_Config($cfg);' . PHP_EOL
                       . PHP_EOL
                       . '</pre>' . PHP_EOL
                    ;


		            // The form has been submitted. Demo the selected method.
		            $libraryMethod = (string) $input->libraryMethod;

		            /**
		             * This section documents the actual code used for the call.
		             * The final bit of code is added below because it is specific
		             * to each call.
		             */
		            echo '<h2>Code used for ' . $libraryMethod . '() method</h2>' . PHP_EOL
		               . '<pre>' . PHP_EOL
		               . '/**' . PHP_EOL
		               . ' * Finished setting up configuration.' . PHP_EOL
		               . ' * Initialize a \TicketEvolution\Webservice object.' . PHP_EOL
		               . ' */' . PHP_EOL
		               . '$tevo = new \TicketEvolution\Webservice($config->params);' . PHP_EOL
		               . PHP_EOL
		               . PHP_EOL
		               . '/**' . PHP_EOL
		               . ' * Below here is where all the method-specific stuff is.' . PHP_EOL
		               . ' */' . PHP_EOL
		            ;

                    /**
                     * Setup any necessary vars and execute the call
                     */
                    $options = _getOptions($input);
                    //var_dump($options);

                    switch ($libraryMethod) {
                        case 'listBrokerages' :
                        case 'listClients' :
                        case 'listClientCompanies' :
                        case 'listUsers' :
                        case 'listSettingsShipping' :
                        case 'listSettingsServiceFees' :
                        case 'listCategories' :
                        case 'listCategoriesDeleted' :
                        case 'listConfigurations' :
                        case 'listEvents' :
                        case 'listEventsDeleted' :
                        case 'listPerformers' :
                        case 'listPerformersDeleted' :
                        case 'listVenues' :
                        case 'listVenuesDeleted' :
                        case 'listTicketGroups' :
                        case 'listOrders' :
                        case 'listQuotes' :
                        case 'listShipments' :
                        case 'listEvoPayAccounts' :
                            _outputListCode($libraryMethod, $options);
                            $results = _doList($tevo, $libraryMethod, $options);
                            break;

                        case 'showBrokerage' :
                            $showId = $options['brokerage_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'searchBrokerages' :
                            $queryString = $options['q'];
                            unset($options['q']);
                            _outputSearchCode($libraryMethod, $queryString, $options);
                            $results = _doSearch($tevo, $libraryMethod, $queryString, $options);
                            break;

                        case 'showClient' :
                            $showId = $options['client_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'createClients' :
                            $client = new stdClass;
                            $client->name = $options['name'];

                            if (!empty($options['office_id'])) {
                                $client->office_id = $options['office_id'];
                            }

                            if (!empty($options['tags'])) {
                                $client->tags = array(explode(',', $options['hidden-tags']));
                            }

                            // Clients must be passed in an array, even if there is only one
                            $clients[] = $client;

                            // Display the code
                            echo '$client = new stdClass;' . PHP_EOL
                               . '$client->name = \'' . $options['name'] . '\';' . PHP_EOL
                               ;

                            if (!empty($options['office_id'])) {
                                echo '$client->office_id = \'' . $options['office_id'] . '\';' . PHP_EOL;
                            }

                            if (!empty($options['tags'])) {
                                echo '$client->tags = array(explode(\',\', ' . $options['hidden-tags'] . '));' . PHP_EOL;
                            }

                           echo PHP_EOL
                               . '// Clients must be passed in an array, even if there is only one' . PHP_EOL
                               . '$clients[] = $client;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '($clients);' . PHP_EOL
                            ;

                            $results = _doCreate($tevo, $libraryMethod, $clients);
                            break;

                        case 'updateClient' :
                            $updateId = $options['client_id'];

                            $client = new stdClass;
                            $client->name = $options['name'];
                            $client->office_id = $options['office_id'];
                            $client->primary_shipping_address_id = $options['primary_shipping_address_id'];
                            $client->primary_credit_card_id = $options['primary_credit_card_id'];
                            $client->tags = array(explode(',', $options['hidden-tags']));

                            // Display the code
                            echo '$client = new stdClass;' . PHP_EOL
                               . '$client->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . '$client->office_id = \'' . $options['office_id'] . '\';' . PHP_EOL
                               . '$client->primary_shipping_address_id = \'' . $options['primary_shipping_address_id'] . '\';' . PHP_EOL
                               . '$client->primary_credit_card_id = \'' . $options['primary_credit_card_id'] . '\';' . PHP_EOL
                               . '$client->tags = array(explode(\',\', ' . $options['hidden-tags'] . '));' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $updateId . ', $client);' . PHP_EOL
                            ;

                            $results = _doUpdate($tevo, $libraryMethod, $updateId, $client);
                            break;

                        case 'showClientCompany' :
                            $showId = $options['company_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'createClientCompanies' :
                            $company = new stdClass;
                            $company->name = $options['name'];

                            // Companies must be passed in an array, even if there is only one
                            $companies[] = $company;


                            // Display the code
                            echo '$company = new stdClass;' . PHP_EOL
                               . '$company->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '// Companies must be passed in an array, even if there is only one' . PHP_EOL
                               . '$companies[] = $company;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '($companies);' . PHP_EOL
                            ;

                            $results = _doCreate($tevo, $libraryMethod, $companies);
                            break;

                        case 'updateClientCompany' :
                            $updateId = $options['company_id'];

                            $company = new stdClass;
                            $company->name = $options['name'];

                            // Display the code
                            echo '$company = new stdClass;' . PHP_EOL
                               . '$company->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $updateId . ', $company);' . PHP_EOL
                            ;

                            $results = _doUpdate($tevo, $libraryMethod, $updateId, $company);
                            break;

                        case 'listClientAddresses':
                        case 'listClientPhoneNumbers' :
                        case 'listClientEmailAddresses' :
                        case 'listClientCreditCards' :
                            $listId = $options['client_id'];
                            unset($options['client_id']);
                            _outputListByIdCode($libraryMethod, $listId, $options);
                            $results = _doListById($tevo, $libraryMethod, $listId, $options);
                            break;

                        case 'showClientAddress':
                            $client_id = $options['client_id'];
                            $showId = $options['address_id'];

                            _outputShowByIdCode($libraryMethod, $client_id, $showId);
                            $results = _doShowById($tevo, $libraryMethod, $client_id, $showId);
                            break;

                        case 'createClientAddresses':
                            $client_id = $options['client_id'];

                            $address = new stdClass;
                            $address->label = $options['label'];
                            $address->name = $options['name'];
                            $address->company = $options['company'];
                            $address->street_address = $options['street_address'];
                            $address->extended_address = $options['extended_address'];
                            $address->locality = $options['locality'];
                            $address->region = $options['region'];
                            $address->postal_code = $options['postal_code'];
                            $address->country_code = $options['country_code'];
                            $address->primary = (bool) $options['primary'];

                            // Addresses must be passed in an array, even if there is only one
                            $addresses[] = $address;

                            // Display the code
                            echo '$address = new stdClass;' . PHP_EOL
                               . '$address->label = \'' . $options['label'] . '\';' . PHP_EOL
                               . '$address->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . '$address->company = \'' . $options['company'] . '\';' . PHP_EOL
                               . '$address->street_address = \'' . $options['street_address'] . '\';' . PHP_EOL
                               . '$address->extended_address = \'' . $options['extended_address'] . '\';' . PHP_EOL
                               . '$address->locality = \'' . $options['locality'] . '\';' . PHP_EOL
                               . '$address->region = \'' . $options['region'] . '\';' . PHP_EOL
                               . '$address->postal_code = \'' . $options['postal_code'] . '\';' . PHP_EOL
                               . '$address->country_code = \'' . $options['country_code'] . '\';' . PHP_EOL
                               . '$address->primary = (bool) ' . $options['primary'] . ';' . PHP_EOL
                               . PHP_EOL
                               . '// Addresses must be passed in an array, even if there is only one' . PHP_EOL
                               . '$addresses[] = $address;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '($addresses);' . PHP_EOL
                            ;

                            $results = _doCreateById($tevo, $libraryMethod, $client_id, $addresses);
                            break;

                        case 'updateClientAddress' :
                            $itemId = $options['client_id'];
                            $updateId = $options['address_id'];

                            $address = new stdClass;
                            $address->label = $options['label'];
                            $address->name = $options['name'];
                            $address->company = $options['company'];
                            $address->street_address = $options['street_address'];
                            $address->extended_address = $options['extended_address'];
                            $address->locality = $options['locality'];
                            $address->region = $options['region'];
                            $address->postal_code = $options['postal_code'];
                            $address->country_code = $options['country_code'];
                            $address->primary = (bool) $options['primary'];

                            // Display the code
                            echo '$address = new stdClass;' . PHP_EOL
                               . '$address->label = \'' . $options['label'] . '\';' . PHP_EOL
                               . '$address->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . '$address->company = \'' . $options['company'] . '\';' . PHP_EOL
                               . '$address->street_address = \'' . $options['street_address'] . '\';' . PHP_EOL
                               . '$address->extended_address = \'' . $options['extended_address'] . '\';' . PHP_EOL
                               . '$address->locality = \'' . $options['locality'] . '\';' . PHP_EOL
                               . '$address->region = \'' . $options['region'] . '\';' . PHP_EOL
                               . '$address->postal_code = \'' . $options['postal_code'] . '\';' . PHP_EOL
                               . '$address->country_code = \'' . $options['country_code'] . '\';' . PHP_EOL
                               . '$address->primary = (bool) ' . $options['primary'] . ';' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $itemId . ', ' . $updateId . ', $address);' . PHP_EOL
                            ;

                            $results = _doUpdateById($tevo, $libraryMethod, $itemId, $updateId, $address);
                            break;

                        case 'showClientPhoneNumber':
                            $client_id = $options['client_id'];
                            $showId = $options['phone_number_id'];

                            _outputShowByIdCode($libraryMethod, $client_id, $showId);
                            $results = _doShowById($tevo, $libraryMethod, $client_id, $showId);
                            break;

                        case 'createClientPhoneNumbers':
                            $client_id = $options['client_id'];

                            $phoneNumber = new stdClass;
                            $phoneNumber->label = $options['label'];
                            $phoneNumber->country_code = $options['country_code'];
                            $phoneNumber->number = $options['number'];
                            $phoneNumber->extension = $options['extension'];

                            // Phone Numbers must be passed in an array, even if there is only one
                            $phoneNumbers[] = $phoneNumber;

                            // Display the code
                            echo '$phoneNumber = new stdClass;' . PHP_EOL
                               . '$phoneNumber->label = \'' . $options['label'] . '\';' . PHP_EOL
                               . '$phoneNumber->country_code = \'' . $options['country_code'] . '\';' . PHP_EOL
                               . '$phoneNumber->number = \'' . $options['number'] . '\';' . PHP_EOL
                               . '$phoneNumber->extension = \'' . $options['extension'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '// Phone Numbers must be passed in an array, even if there is only one' . PHP_EOL
                               . '$phoneNumbers[] = $phoneNumber;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $client_id . ', $phoneNumbers);' . PHP_EOL
                            ;

                            $results = _doCreateById($tevo, $libraryMethod, $client_id, $phoneNumbers);
                            break;

                        case 'updateClientPhoneNumber' :
                            $itemId = $options['client_id'];
                            $updateId = $options['phone_number_id'];

                            $phoneNumber = new stdClass;
                            $phoneNumber->label = $options['label'];
                            $phoneNumber->country_code = $options['country_code'];
                            $phoneNumber->number = $options['number'];
                            $phoneNumber->extension = $options['extension'];

                            // Display the code
                            echo '$phoneNumber = new stdClass;' . PHP_EOL
                               . '$phoneNumber->label = \'' . $options['label'] . '\';' . PHP_EOL
                               . '$phoneNumber->country_code = \'' . $options['country_code'] . '\';' . PHP_EOL
                               . '$phoneNumber->number = \'' . $options['number'] . '\';' . PHP_EOL
                               . '$phoneNumber->extension = \'' . $options['extension'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $itemId . ', ' . $updateId . ', $phoneNumber);' . PHP_EOL
                            ;

                            $results = _doUpdateById($tevo, $libraryMethod, $itemId, $updateId, $phoneNumber);
                            break;

                        case 'showClientEmailAddress':
                            $client_id = $options['client_id'];
                            $showId = $options['email_address_id'];

                            _outputShowByIdCode($libraryMethod, $client_id, $showId);
                            $results = _doShowById($tevo, $libraryMethod, $client_id, $showId);
                            break;

                        case 'createClientEmailAddresses':
                            $client_id = $options['client_id'];

                            $emailAddress = new stdClass;
                            $emailAddress->label = $options['label'];
                            $emailAddress->address = $options['address'];

                            // Email Addresses must be passed in an array, even if there is only one
                            $emailAddresses[] = $emailAddress;

                            // Display the code
                            echo '$emailAddress = new stdClass;' . PHP_EOL
                               . '$emailAddress->label = \'' . $options['label'] . '\';' . PHP_EOL
                               . '$emailAddress->address = \'' . $options['address'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '// Email Addresses must be passed in an array, even if there is only one' . PHP_EOL
                               . '$emailAddresses[] = $emailAddress;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $client_id . ', $emailAddresses);' . PHP_EOL
                            ;

                            $results = _doCreateById($tevo, $libraryMethod, $client_id, $emailAddresses);
                            break;

                        case 'updateClientEmailAddress' :
                            $itemId = $options['client_id'];
                            $updateId = $options['email_address_id'];

                            $emailAddress = new stdClass;
                            $emailAddress->label = $options['label'];
                            $emailAddress->address = $options['address'];

                            // Email Addresses must be passed in an array, even if there is only one
                            $emailAddresses[] = $emailAddress;

                            // Display the code
                            echo '$emailAddress = new stdClass;' . PHP_EOL
                               . '$emailAddress->label = \'' . $options['label'] . '\';' . PHP_EOL
                               . '$emailAddress->address = \'' . $options['address'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '// Email Addresses must be passed in an array, even if there is only one' . PHP_EOL
                               . '$emailAddresses[] = $emailAddress;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $itemId . ', ' . $updateId . ', $emailAddresses);' . PHP_EOL
                            ;

                            $results = _doUpdateById($tevo, $libraryMethod, $itemId, $updateId, $emailAddresses);
                            break;

                        case 'showClientCreditCard':
                            $client_id = $options['client_id'];
                            $showId = $options['credit_card_id'];

                            _outputShowByIdCode($libraryMethod, $client_id, $showId);
                            $results = _doShowById($tevo, $libraryMethod, $client_id, $showId);
                            break;

                        case 'createClientCreditCards':
                            $client_id = $options['client_id'];

                            $creditCard = new stdClass;
                            $creditCard->address_id = $options['address_id'];
                            $creditCard->phone_number_id = $options['phone_number_id'];
                            $creditCard->name = $options['name'];
                            $creditCard->number = $options['number'];
                            $creditCard->expiration_month = $options['expiration_month'];
                            $creditCard->expiration_year = $options['expiration_year'];
                            $creditCard->verification_code = $options['verification_code'];
                            $creditCard->ip_address = $options['ip_address'];

                            // Credit Cards must be passed in an array, even if there is only one
                            $creditCards[] = $creditCard;

                            // Display the code
                            echo '$creditCard = new stdClass;' . PHP_EOL
                               . '$creditCard->address_id = \'' . $options['address_id'] . '\';' . PHP_EOL
                               . '$creditCard->phone_number_id = \'' . $options['phone_number_id'] . '\';' . PHP_EOL
                               . '$creditCard->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . '$creditCard->number = \'' . $options['number'] . '\';' . PHP_EOL
                               . '$creditCard->expiration_month = \'' . $options['expiration_month'] . '\';' . PHP_EOL
                               . '$creditCard->expiration_year = \'' . $options['expiration_year'] . '\';' . PHP_EOL
                               . '$creditCard->verification_code = \'' . $options['verification_code'] . '\';' . PHP_EOL
                               . '$creditCard->ip_address = \'' . $options['ip_address'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '// Credit Cards must be passed in an array, even if there is only one' . PHP_EOL
                               . '$creditCards[] = $creditCard;' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $client_id . ', $creditCards);' . PHP_EOL
                            ;

                            $results = _doCreateById($tevo, $libraryMethod, $client_id, $creditCards);
                            break;

                        case 'updateClientCreditCard' :
                            $itemId = $options['client_id'];
                            $updateId = $options['credit_card_id'];

                            $creditCard = new stdClass;
                            $creditCard->address_id = $options['address_id'];
                            $creditCard->phone_number_id = $options['phone_number_id'];
                            $creditCard->name = $options['name'];
                            $creditCard->number = $options['number'];
                            $creditCard->expiration_month = $options['expiration_month'];
                            $creditCard->expiration_year = $options['expiration_year'];
                            $creditCard->verification_code = $options['verification_code'];
                            $creditCard->ip_address = $options['ip_address'];

                            // Display the code
                            echo '$creditCard = new stdClass;' . PHP_EOL
                               . '$creditCard->address_id = \'' . $options['address_id'] . '\';' . PHP_EOL
                               . '$creditCard->phone_number_id = \'' . $options['phone_number_id'] . '\';' . PHP_EOL
                               . '$creditCard->name = \'' . $options['name'] . '\';' . PHP_EOL
                               . '$creditCard->number = \'' . $options['number'] . '\';' . PHP_EOL
                               . '$creditCard->expiration_month = \'' . $options['expiration_month'] . '\';' . PHP_EOL
                               . '$creditCard->expiration_year = \'' . $options['expiration_year'] . '\';' . PHP_EOL
                               . '$creditCard->verification_code = \'' . $options['verification_code'] . '\';' . PHP_EOL
                               . '$creditCard->ip_address = \'' . $options['ip_address'] . '\';' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '(' . $itemId . ', ' . $updateId . ', $creditCard);' . PHP_EOL
                            ;

                            $results = _doUpdateById($tevo, $libraryMethod, $itemId, $updateId, $creditCard);
                            break;

                        case 'showOffice' :
                            $showId = $options['office_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'searchQuotes' :
                        case 'searchOffices' :
                        case 'searchUsers' :
                        case 'searchPerformers' :
                        case 'searchVenues' :
                        case 'search' :
                            $queryTerm = $options['q'];
                            if (isset($options['types']) && is_array($options['types'])) {
                                $options['types'] = implode(',', $options['types']);
                            }

                            _outputSearchCode($libraryMethod, $queryTerm, $options);
                            $results = _doSearch($tevo, $libraryMethod, $queryTerm, $options);
                            break;

                        case 'showUser' :
                            $showId = $options['user_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showCategory' :
                            $showId = $options['category_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showConfiguration' :
                            $showId = $options['configuration_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showEvent' :
                            $showId = $options['event_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showPerformer' :
                            $showId = $options['performer_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showVenue' :
                            $showId = $options['venue_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showTicketGroup' :
                            $showId = $options['ticket_group_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showOrder' :
                            $showId = $options['order_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showQuote' :
                            $showId = $options['quote_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'showShipment' :
                            $showId = $options['shipment_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'createShipments' :
                            if ($_FILES['airbill']['error'] == 0) {
                                $options['airbill'] = base64_encode(file_get_contents($_FILES['airbill']['tmp_name']));
                            } else {
                                // Throw Exception
                            }

                            echo 'if ($_FILES[\'airbill\'][\'error\'] == 0) {' . PHP_EOL
                               . '    $options[\'airbill\'] = base64_encode(file_get_contents($_FILES[\'airbill\'][\'tmp_name\']));' . PHP_EOL
                               . '} else {' . PHP_EOL
                               . '    throw new Exception(\'You had an error uploading your airbill.\');' . PHP_EOL
                               . '}' . PHP_EOL
                               . PHP_EOL
                            ;
                            _outputListCode($libraryMethod, $options);

                            $results = _doCreate($tevo, $libraryMethod, $options);
                            break;

                        case 'updateShipment' :
                            if ($_FILES['airbill']['error'] == 0) {
                                $options['airbill'] = base64_encode(file_get_contents($_FILES['airbill']['tmp_name']));
                            } else {
                                // Throw Exception
                            }

                            echo 'if ($_FILES[\'airbill\'][\'error\'] == 0) {' . PHP_EOL
                               . '    $options[\'airbill\'] = base64_encode(file_get_contents($_FILES[\'airbill\'][\'tmp_name\']));' . PHP_EOL
                               . '} else {' . PHP_EOL
                               . '    throw new Exception(\'You had an error uploading your airbill.\');' . PHP_EOL
                               . '}' . PHP_EOL
                               . PHP_EOL
                            ;
                            _outputListCode($libraryMethod, $options);

                            $results = _doUpdate($tevo, $libraryMethod, $options);
                            break;

                        case 'showEvoPayAccount' :
                            $showId = $options['account_id'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($tevo, $libraryMethod, $showId);
                            break;

                        case 'listEvoPayTransactions' :
                            $listId = $options['account_id'];
                            unset($options['account_id']);
                            _outputListByIdCode($libraryMethod, $listId, $options);
                            $results = _doListById($tevo, $libraryMethod, $listId, $options);
                            break;

                        case 'showEvoPayTransaction' :
                            $accountId = $options['account_id'];
                            $transactionId = $options['transaction_id'];
                            _outputShowByIdCode($libraryMethod, $accountId, $transactionId);
                            $results = _doShowById($tevo, $libraryMethod, $accountId, $transactionId);
                            break;

                        case 'createOrdersClient' :
                            $payment = new stdClass;
                            $payment->type = $options['payments'];

                            unset($options['payments']);
                            $options['payments'][] = $payment;

                            echo '$options = array(' . PHP_EOL;
                            foreach( $options as $key => $val) {
                                if (!is_array($val) && !is_object($val)) {
                                    echo '    \'' . $key . '\' => ' . $val . ',' . PHP_EOL;
                                }
                            }
                            echo ');' . PHP_EOL . PHP_EOL;

                            $item = new stdClass;
                            $item->ticket_group_id = $_REQUEST['items'][0]['ticket_group_id'];
                            $item->quantity = (int) $_REQUEST['items'][0]['quantity'];
                            $item->price = $_REQUEST['items'][0]['price'];
                            $items[] = $item;

                            if (!empty($_REQUEST['items'][1]['ticket_group_id'])) {
                                $item = new stdClass;
                                $item->ticket_group_id = $_REQUEST['items'][1]['ticket_group_id'];
                                $item->quantity = (int) $_REQUEST['items'][1]['quantity'];
                                $item->price = $_REQUEST['items'][1]['price'];
                                $items[] = $item;
                            }
                            $options['items'] = $items;

                            if (empty($_REQUEST['shipping_address_id'])) {
                                $address = new stdClass;
                                $address->label = $_REQUEST['shipping_address']['label'];
                                $address->name = $_REQUEST['shipping_address']['name'];
                                $address->company = $_REQUEST['shipping_address']['company'];
                                $address->street_address = $_REQUEST['shipping_address']['street_address'];
                                $address->extended_address = $_REQUEST['shipping_address']['extended_address'];
                                $address->locality = $_REQUEST['shipping_address']['locality'];
                                $address->region = $_REQUEST['shipping_address']['region'];
                                $address->postal_code = $_REQUEST['shipping_address']['postal_code'];
                                $address->country_code = $_REQUEST['shipping_address']['country_code'];

                                $options['shipping_address'] = $address;

                                unset($options['shipping_address_id']);
                            } else {
                                unset($options['shipping_address']);
                            }

                            if (empty($_REQUEST['billing_address_id'])) {
                                $address = new stdClass;
                                $address->label = $_REQUEST['billing_address']['label'];
                                $address->name = $_REQUEST['billing_address']['name'];
                                $address->company = $_REQUEST['billing_address']['company'];
                                $address->street_address = $_REQUEST['billing_address']['street_address'];
                                $address->extended_address = $_REQUEST['billing_address']['extended_address'];
                                $address->locality = $_REQUEST['billing_address']['locality'];
                                $address->region = $_REQUEST['billing_address']['region'];
                                $address->postal_code = $_REQUEST['billing_address']['postal_code'];
                                $address->country_code = $_REQUEST['billing_address']['country_code'];

                                $options['billing_address'] = $address;

                                unset($options['billing_address_id']);
                            } else {
                                unset($options['billing_address']);
                            }

                            $orders[] = $options;

                            echo '$payment = new stdClass;' . PHP_EOL
                               . '$payment->type = ' . $_REQUEST['payments'] . ';' . PHP_EOL

                               . '$options[\'payments\'][] = $payment;' . PHP_EOL
                               . PHP_EOL
                            ;

                            echo '$item = new stdClass;' . PHP_EOL
                               . '$item->ticket_group_id = ' . $_REQUEST['items'][0]['ticket_group_id'] . ';' . PHP_EOL
                               . '$item->quantity = (int) ' . $_REQUEST['items'][0]['quantity'] . ';' . PHP_EOL
                               . '$item->price = ' . $_REQUEST['items'][0]['price'] . ';' . PHP_EOL
                               . '$options[\'items\'][] = $item;' . PHP_EOL
                               . PHP_EOL
                            ;
                            if (!empty($_REQUEST['items'][1]['ticket_group_id'])) {
                                echo '$item = new stdClass;' . PHP_EOL
                                   . '$item->ticket_group_id = ' . $_REQUEST['items'][1]['ticket_group_id'] . ';' . PHP_EOL
                                   . '$item->quantity = (int) ' . $_REQUEST['items'][1]['quantity'] . ';' . PHP_EOL
                                   . '$item->price = ' . $_REQUEST['items'][1]['price'] . ';' . PHP_EOL
                                   . '$options[\'items\'][] = $item;' . PHP_EOL
                                   . PHP_EOL
                                ;
                            }

                            if (empty($_REQUEST['shipping_address_id'])) {
                                echo '$address = new stdClass;' . PHP_EOL
                                   . '$address->label = \'' . $_REQUEST['shipping_address']['label'] . '\';' . PHP_EOL
                                   . '$address->name = \'' . $_REQUEST['shipping_address']['name'] . '\';' . PHP_EOL
                                   . '$address->company = \'' . $_REQUEST['shipping_address']['company'] . '\';' . PHP_EOL
                                   . '$address->street_address = \'' . $_REQUEST['shipping_address']['street_address'] . '\';' . PHP_EOL
                                   . '$address->extended_address = \'' . $_REQUEST['shipping_address']['extended_address'] . '\';' . PHP_EOL
                                   . '$address->locality = \'' . $_REQUEST['shipping_address']['locality'] . '\';' . PHP_EOL
                                   . '$address->region = \'' . $_REQUEST['shipping_address']['region'] . '\';' . PHP_EOL
                                   . '$address->postal_code = \'' . $_REQUEST['shipping_address']['postal_code'] . '\';' . PHP_EOL
                                   . '$address->country_code = \'' . $_REQUEST['shipping_address']['country_code'] . '\';' . PHP_EOL
                                   . PHP_EOL
                                   . '$options[\'shipping_address\'] = $address;' . PHP_EOL
                                   . PHP_EOL
                                ;
                            }

                            if (empty($_REQUEST['billing_address_id'])) {
                                echo '$address = new stdClass;' . PHP_EOL
                                   . '$address->label = \'' . $_REQUEST['billing_address']['label'] . '\';' . PHP_EOL
                                   . '$address->name = \'' . $_REQUEST['billing_address']['name'] . '\';' . PHP_EOL
                                   . '$address->company = \'' . $_REQUEST['billing_address']['company'] . '\';' . PHP_EOL
                                   . '$address->street_address = \'' . $_REQUEST['billing_address']['street_address'] . '\';' . PHP_EOL
                                   . '$address->extended_address = \'' . $_REQUEST['billing_address']['extended_address'] . '\';' . PHP_EOL
                                   . '$address->locality = \'' . $_REQUEST['billing_address']['locality'] . '\';' . PHP_EOL
                                   . '$address->region = \'' . $_REQUEST['billing_address']['region'] . '\';' . PHP_EOL
                                   . '$address->postal_code = \'' . $_REQUEST['billing_address']['postal_code'] . '\';' . PHP_EOL
                                   . '$address->country_code = \'' . $_REQUEST['billing_address']['country_code'] . '\';' . PHP_EOL
                                   . PHP_EOL
                                   . '$options[\'billing_address\'] = $address;' . PHP_EOL
                                   . PHP_EOL
                                ;
                            }

                            echo '$orders[] = $options;' . PHP_EOL;

                            //var_dump($orders);
                            $results = _doCreate($tevo, 'createOrders', $orders);
                            break;


                        case 'updateOrder' :
                            $updateId = $options['order_id'];

                            $order = new stdClass;
                            $order->po_number = $options['po_number'];
                            $order->invoice_number = $options['invoice_number'];
                            $order->instructions = $options['instructions'];

                            if (empty($_REQUEST['shipping_address_id'])) {
                                $address = new stdClass;
                                $address->label = $_REQUEST['shipping_address']['label'];
                                $address->name = $_REQUEST['shipping_address']['name'];
                                $address->company = $_REQUEST['shipping_address']['company'];
                                $address->street_address = $_REQUEST['shipping_address']['street_address'];
                                $address->extended_address = $_REQUEST['shipping_address']['extended_address'];
                                $address->locality = $_REQUEST['shipping_address']['locality'];
                                $address->region = $_REQUEST['shipping_address']['region'];
                                $address->postal_code = $_REQUEST['shipping_address']['postal_code'];
                                $address->country_code = $_REQUEST['shipping_address']['country_code'];

                                $order->shipping_address = $address;

                            } else {
                                $order->shipping_address_id = $options['shipping_address_id'];
                            }

                            if (empty($_REQUEST['billing_address_id'])) {
                                $address = new stdClass;
                                $address->label = $_REQUEST['billing_address']['label'];
                                $address->name = $_REQUEST['billing_address']['name'];
                                $address->company = $_REQUEST['billing_address']['company'];
                                $address->street_address = $_REQUEST['billing_address']['street_address'];
                                $address->extended_address = $_REQUEST['billing_address']['extended_address'];
                                $address->locality = $_REQUEST['billing_address']['locality'];
                                $address->region = $_REQUEST['billing_address']['region'];
                                $address->postal_code = $_REQUEST['billing_address']['postal_code'];
                                $address->country_code = $_REQUEST['billing_address']['country_code'];

                                $order->billing_address = $address;

                            } else {
                                $order->billing_address_id = $options['billing_address_id'];
                            }


                            echo '$order = new stdClass;' . PHP_EOL
                               . '$order->po_number = ' . $options['po_number'] . ';' . PHP_EOL
                               . '$order->invoice_number = ' . $options['invoice_number'] . ';' . PHP_EOL
                               . '$order->instructions = \'' . $options['instructions'] . '\';' . PHP_EOL
                               . PHP_EOL
                               ;
                            if (empty($_REQUEST['shipping_address_id'])) {
                                echo '$address = new stdClass;' . PHP_EOL
                                   . '$address->label = \'' . $_REQUEST['shipping_address']['label'] . '\';' . PHP_EOL
                                   . '$address->name = \'' . $_REQUEST['shipping_address']['name'] . '\';' . PHP_EOL
                                   . '$address->company = \'' . $_REQUEST['shipping_address']['company'] . '\';' . PHP_EOL
                                   . '$address->street_address = \'' . $_REQUEST['shipping_address']['street_address'] . '\';' . PHP_EOL
                                   . '$address->extended_address = \'' . $_REQUEST['shipping_address']['extended_address'] . '\';' . PHP_EOL
                                   . '$address->locality = \'' . $_REQUEST['shipping_address']['locality'] . '\';' . PHP_EOL
                                   . '$address->region = \'' . $_REQUEST['shipping_address']['region'] . '\';' . PHP_EOL
                                   . '$address->postal_code = \'' . $_REQUEST['shipping_address']['postal_code'] . '\';' . PHP_EOL
                                   . '$address->country_code = \'' . $_REQUEST['shipping_address']['country_code'] . '\';' . PHP_EOL
                                   . PHP_EOL
                                   . '$order->shipping_address = $address;' . PHP_EOL
                                   . PHP_EOL
                                ;
                            } else {
                                echo '$order->shipping_address_id = ' . $options['shipping_address_id'] . ';' . PHP_EOL;
                            }

                            if (empty($_REQUEST['billing_address_id'])) {
                                echo '$address = new stdClass;' . PHP_EOL
                                   . '$address->label = \'' . $_REQUEST['billing_address']['label'] . '\';' . PHP_EOL
                                   . '$address->name = \'' . $_REQUEST['billing_address']['name'] . '\';' . PHP_EOL
                                   . '$address->company = \'' . $_REQUEST['billing_address']['company'] . '\';' . PHP_EOL
                                   . '$address->street_address = \'' . $_REQUEST['billing_address']['street_address'] . '\';' . PHP_EOL
                                   . '$address->extended_address = \'' . $_REQUEST['billing_address']['extended_address'] . '\';' . PHP_EOL
                                   . '$address->locality = \'' . $_REQUEST['billing_address']['locality'] . '\';' . PHP_EOL
                                   . '$address->region = \'' . $_REQUEST['billing_address']['region'] . '\';' . PHP_EOL
                                   . '$address->postal_code = \'' . $_REQUEST['billing_address']['postal_code'] . '\';' . PHP_EOL
                                   . '$address->country_code = \'' . $_REQUEST['billing_address']['country_code'] . '\';' . PHP_EOL
                                   . PHP_EOL
                                   . '$order->billing_address = $address;' . PHP_EOL
                                   . PHP_EOL
                                ;
                            } else {
                                echo '$order->billing_address_id = ' . $options['billing_address_id'] . ';' . PHP_EOL;
                            }

                            echo '$results = $tevo->' . $libraryMethod . '(' . $updateId . ', $order);' . PHP_EOL
                               ;


                            //var_dump($orders);
                            $results = _doUpdate($tevo, 'updateOrder', $updateId, $order);
                            break;


                        case 'acceptOrder' :
                            $orderId = $options['order_id'];
                            $reviewerId = $options['reviewer_id'];

                            echo '$results = $tevo->' . $libraryMethod . '(' . $orderId . ', ' . $reviewerId . ');' . PHP_EOL;

                            $results = _doOther($tevo, 'acceptOrder', $orderId, $reviewerId);
                            break;


                        case 'rejectOrder' :
                            $orderId = $options['order_id'];
                            $reviewerId = $options['reviewer_id'];
                            $rejectionReason = $options['rejection_reason'];

                            echo '$results = $tevo->' . $libraryMethod . '(' . $orderId . ', ' . $reviewerId . ', \'' . $rejectionReason . '\');' . PHP_EOL;

                            $results = _doOther($tevo, 'rejectOrder', $orderId, $reviewerId, $rejectionReason);
                            break;


                        case 'completeOrder' :
                            $orderId = $options['order_id'];

                            echo '$results = $tevo->' . $libraryMethod . '(' . $orderId . ');' . PHP_EOL;

                            $results = _doOther($tevo, 'completeOrder', $orderId);
                            break;












                        default:

                            // Display the code
                            echo '$options = array(' . PHP_EOL;
                            foreach ($options as $key => $val) {
                                echo '    \'' . $key . '\' => ' . $val . ',' . PHP_EOL;
                            }
                            echo ');' . PHP_EOL
                               . PHP_EOL
                               . '$results = $tevo->' . $libraryMethod . '($options);' . PHP_EOL
                            ;

                            // Execute the call
                            try {
                                $results = $tevo->$libraryMethod($options);
                            } catch (Exception $e) {
                                $results = false;
                            }
                            break;
                    }

                    echo '</pre>' . PHP_EOL; // Close up the echoing of the code used

                    // Display the results
                    if ($results) {
                        if ($libraryMethod == 'acceptOrder') {
                            if ($results) {
                                echo '<h2>Order Successfully Confirmed</h2>' . PHP_EOL;
                            } else {
                                echo '<h2>Error Confirming Order</h2>' . PHP_EOL;
                            }
                        } elseif ($libraryMethod == 'rejectOrder') {
                            if ($results) {
                                echo '<h2>Order Successfully Rejected</h2>' . PHP_EOL;
                            } else {
                                echo '<h2>Error Confirming Order</h2>' . PHP_EOL;
                            }
                        } else {
                            echo _getRequest($tevo, $libraryMethod, false);
                            echo _getResponse($tevo, $libraryMethod, false);

                            echo '<h2>Results of ' . $libraryMethod . '() method</h2>' . PHP_EOL;
                            if($results instanceof Countable) {
                                echo '<p>There are ' . count($results) . ' results available.</p>' . PHP_EOL;
                                foreach ($results as $result) {
                                    echo '<pre>';
                                    print_r ($result);
                                    echo '</pre><br />' . PHP_EOL;
                                }
                            } else {
                                echo '<pre>';
                                print_r ($results);
                                echo '</pre><br />' . PHP_EOL;
                            }
                            echo '<h2>print_r() of ' . get_class ($results) . ' result object</h2>' . PHP_EOL
                               . '<p>This shows all the public and protected properties of the full '
                               . '<strong>' . get_class ($results) . '</strong> object that is returned from the '
                               . '<strong>' . $libraryMethod . '()</strong> call. Each method will return different '
                               . 'types of objects depending on what the data returned is.</p>'

                               . '<pre>'; print_r($results); echo '</pre>' . PHP_EOL
                            ;
                        }
                    } else {
                        echo '</pre>' . PHP_EOL
                           . '<h1>Exception thrown trying to perform API request</h1>' . PHP_EOL
                           . _getRequest($tevo, $libraryMethod, true)
                           . _getResponse($tevo, $libraryMethod, true);
                    }
		        }
		    ?>
		    <form action="index.php" method="get" target="_top" class="form-horizontal" onsubmit="checkForm();" enctype="multipart/form-data">
		        <fieldset id="environmentAndCredentials">
                    <legend>Environment and Credentials</legend>

                    <div class="control-group">
                        <label class="control-label" for="environment">API Environment</label>
                        <div class="controls">
                            <select name="environment" id="environment" onchange="changeEnvironment();">
                                <option value="sandbox"<?php if (@$input->environment == 'sandbox') { echo ' selected="selected"';} ?>>Sandbox</option>
                                <option value="staging"<?php if (@$input->environment == 'staging') { echo ' selected="selected"';} ?>>Staging</option>
                                <option value="production"<?php if (@$input->environment == 'production') { echo ' selected="selected"';} ?>>Production</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="apiVersion">API Version</label>
                        <div class="controls">
                            <input class="input-mini" name="apiVersion" id="apiVersion" type="text" value="9" size="2" readonly="readonly" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Your API Token</label>
                        <div class="controls">
                            <input class="apiToken input-xxlarge sandbox" name="apiToken" type="text" value="<?php if (!empty($_SESSION['sandbox']['apiToken'])) {echo $_SESSION['sandbox']['apiToken'];} ?>" />
                            <input class="apiToken input-xxlarge staging" name="apiToken" type="text" value="<?php if (!empty($_SESSION['staging']['apiToken'])) {echo $_SESSION['staging']['apiToken'];} ?>" />
                            <input class="apiToken input-xxlarge production" name="apiToken" type="text" value="<?php if (!empty($_SESSION['production']['apiToken'])) {echo $_SESSION['production']['apiToken'];} ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Your API Secret Key</label>
                        <div class="controls">
                            <input class="secretKey input-xxlarge sandbox" name="secretKey" type="text" value="<?php if (!empty($_SESSION['sandbox']['secretKey'])) {echo $_SESSION['sandbox']['secretKey'];} ?>" />
                            <input class="secretKey input-xxlarge staging" name="secretKey" type="text" value="<?php if (!empty($_SESSION['staging']['secretKey'])) {echo $_SESSION['staging']['secretKey'];} ?>" />
                            <input class="secretKey input-xxlarge production" name="secretKey" type="text" value="<?php if (!empty($_SESSION['production']['secretKey'])) {echo $_SESSION['production']['secretKey'];} ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Your Buyer ID (a/k/a office_id)</label>
                        <div class="controls">
                            <input class="buyerId input-small sandbox" name="buyerId" type="text" value="<?php if (!empty($_SESSION['sandbox']['buyerId'])) {echo $_SESSION['sandbox']['buyerId'];} ?>" />
                            <input class="buyerId input-small staging" name="buyerId" type="text" value="<?php if (!empty($_SESSION['staging']['buyerId'])) {echo $_SESSION['staging']['buyerId'];} ?>" />
                            <input class="buyerId input-small production" name="buyerId" type="text" value="<?php if (!empty($_SESSION['production']['buyerId'])) {echo $_SESSION['production']['buyerId'];} ?>" />
                        </div>
                    </div>


                </fieldset>

		        <fieldset>
                    <legend>PHP Library Method</legend>

                    <div class="control-group">
                        <label class="control-label" for="libraryMethod">PHP Library Method</label>
                        <div class="controls">
                            <select id="libraryMethod" name="libraryMethod" size="1" onchange="toggleOptions();">
                                <option label="Select a method…" value="">Select a method…</option>

                                <optgroup label="Brokerages Methods">
                                    <option label="listBrokerages()" value="listBrokerages">listBrokerages()</option>
                                    <option label="showBrokerage()" value="showBrokerage">showBrokerage()</option>
                                    <option label="searchBrokerages()" value="searchBrokerages">searchBrokerages()</option>
                                </optgroup>

                                <optgroup label="Settings Methods">
                                    <option label="listSettingsShipping()" value="listSettingsShipping">listSettingsShipping()</option>
                                    <option label="listSettingsServiceFees()" value="listSettingsServiceFees">listSettingsServiceFees()</option>
                                </optgroup>

                                <optgroup label="Offices Methods">
                                    <option label="listOffices()" value="listOffices">listOffices()</option>
                                    <option label="showOffice()" value="showOffice">showOffice()</option>
                                    <option label="searchOffices()" value="searchOffices">searchOffices()</option>
                                </optgroup>

                                <optgroup label="Users Methods">
                                    <option label="listUsers()" value="listUsers">listUsers()</option>
                                    <option label="showUser()" value="showUser">showUser()</option>
                                    <option label="searchUsers()" value="searchUsers">searchUsers()</option>
                                </optgroup>

                                <optgroup label="Clients Methods">
                                    <option label="listClients()" value="listClients">listClients()</option>
                                    <option label="showClient()" value="showClient">showClient()</option>
                                    <option label="createClients()" value="createClients">createClients()</option>
                                    <option label="updateClient()" value="updateClient">updateClient()</option>
                                </optgroup>

                                <optgroup label="Client Company Methods">
                                    <option label="listClientCompanies()" value="listClientCompanies">listClientCompanies()</option>
                                    <option label="showClientCompany()" value="showClientCompany">showClientCompany()</option>
                                    <option label="createClientCompanies()" value="createClientCompanies">createClientCompanies()</option>
                                    <option label="updateClientCompany()" value="updateClientCompany">updateClientCompany()</option>
                                </optgroup>

                                <optgroup label="Client Address Methods">
                                    <option label="listClientAddresses()" value="listClientAddresses">listClientAddresses()</option>
                                    <option label="showClientAddress()" value="showClientAddress">showClientAddress()</option>
                                    <option label="createClientAddresses()" value="createClientAddresses">createClientAddresses()</option>
                                    <option label="updateClientAddress()" value="updateClientAddress">updateClientAddress()</option>
                                </optgroup>

                                <optgroup label="Client Phone Number Methods">
                                    <option label="listClientPhoneNumbers()" value="listClientPhoneNumbers">listClientPhoneNumbers()</option>
                                    <option label="showClientPhoneNumber()" value="showClientPhoneNumber">showClientPhoneNumber()</option>
                                    <option label="createClientPhoneNumbers()" value="createClientPhoneNumbers">createClientPhoneNumbers()</option>
                                    <option label="updateClientPhoneNumber()" value="updateClientPhoneNumber">updateClientPhoneNumber()</option>
                                </optgroup>

                                <optgroup label="Client Email Address Methods">
                                    <option label="listClientEmailAddresses()" value="listClientEmailAddresses">listClientEmailAddresses()</option>
                                    <option label="showClientEmailAddress()" value="showClientEmailAddress">showClientEmailAddress()</option>
                                    <option label="createClientEmailAddresses()" value="createClientEmailAddresses">createClientEmailAddresses()</option>
                                    <option label="updateClientEmailAddress()" value="updateClientEmailAddress">updateClientEmailAddress()</option>
                                </optgroup>

                                <optgroup label="Client Credit Card Methods">
                                    <option label="listClientCreditCards()" value="listClientCreditCards">listClientCreditCards()</option>
                                    <?php
                                        // This endpoint does not (yet?) exist
                                        //<option label="showClientCreditCard()" value="showClientCreditCard">showClientCreditCard()</option>
                                    ?>
                                    <option label="createClientCreditCards()" value="createClientCreditCards">createClientCreditCards()</option>
                                    <?php
                                        // This endpoint does not (yet?) exist
                                        //<option label="updateClientCreditCard()" value="updateClientCreditCard">updateClientCreditCard()</option>
                                    ?>
                                </optgroup>


                                <optgroup label="Categories Methods">
                                    <option label="listCategories()" value="listCategories">listCategories()</option>
                                    <option label="listCategoriesDeleted()" value="listCategoriesDeleted">listCategoriesDeleted()</option>
                                    <option label="showCategory()" value="showCategory">showCategory()</option>
                                </optgroup>

                                <optgroup label="Configurations Methods">
                                    <option label="listConfigurations()" value="listConfigurations">listConfigurations()</option>
                                    <option label="showConfiguration()" value="showConfiguration">showConfiguration()</option>
                                </optgroup>

                                <optgroup label="Events Methods">
                                    <option label="listEvents()" value="listEvents">listEvents()</option>
                                    <option label="listEventsDeleted()" value="listEventsDeleted">listEventsDeleted()</option>
                                    <option label="showEvent()" value="showEvent">showEvent()</option>
                                </optgroup>

                                <optgroup label="Performers Methods">
                                    <option label="listPerformers()" value="listPerformers">listPerformers()</option>
                                    <option label="listPerformersDeleted()" value="listPerformersDeleted">listPerformersDeleted()</option>
                                    <option label="showPerformer()" value="showPerformer">showPerformer()</option>
                                    <option label="searchPerformers()" value="searchPerformers">searchPerformers()</option>
                                </optgroup>

                                <optgroup label="Search Methods">
                                    <option label="search()" value="search">Performers & Venues()</option>
                                </optgroup>

                                <optgroup label="Venues Methods">
                                    <option label="listVenues()" value="listVenues">listVenues()</option>
                                    <option label="listVenuesDeleted()" value="listVenuesDeleted">listVenuesDeleted()</option>
                                    <option label="showVenue()" value="showVenue">showVenue()</option>
                                    <option label="searchVenues()" value="searchVenues">searchVenues()</option>
                                </optgroup>

                                <optgroup label="Ticket Groups">
                                    <option label="listTicketGroups()" value="listTicketGroups">listTicketGroups()</option>
                                    <option label="showTicketGroup()" value="showTicketGroup">showTicketGroup()</option>
                                </optgroup>

                                <optgroup label="Orders Methods">
                                    <option label="listOrders()" value="listOrders">listOrders()</option>
                                    <option label="showOrder()" value="showOrder">showOrder()</option>
                                    <option label="createOrders() (EvoPay)" value="createOrdersEvoPay" disabled="disabled">createOrders() (EvoPay)</option>
                                    <option label="createOrders() (Client)" value="createOrdersClient">createOrders() (Client)</option>
                                    <option label="createFulfillmentOrders()" value="createFulfillmentOrders" disabled="disabled">createFulfillmentOrders()</option>
                                    <option label="updateOrder()" value="updateOrder">updateOrder()</option>
                                    <option label="acceptOrder()" value="acceptOrder">acceptOrder()</option>
                                    <option label="rejectOrder()" value="rejectOrder">rejectOrder()</option>
                                    <option label="completeOrder()" value="completeOrder">completeOrder()</option>
                                </optgroup>

                                <optgroup label="Quotes Methods">
                                    <option label="listQuotes()" value="listQuotes">listQuotes()</option>
                                    <option label="showQuote()" value="showQuote">showQuote()</option>
                                    <option label="searchQuotes()" value="searchQuotes">searchQuotes()</option>
                                </optgroup>

                                <optgroup label="Shipments Methods">
                                    <option label="listShipments()" value="listShipments">listShipments()</option>
                                    <option label="showShipment()" value="showShipment">showShipment()</option>
                                    <option label="createShipments()" value="createShipments">createShipments()</option>
                                    <option label="updateShipment()" value="updateShipment">updateShipment()</option>
                                    <option label="createAirbill()" value="createAirbill" disabled="disabled">createAirbill()</option>
                                </optgroup>

                                <optgroup label="Accounts Methods">
                                    <option label="listEvoPayAccounts()" value="listEvoPayAccounts">listEvoPayAccounts()</option>
                                    <option label="showEvoPayAccount()" value="showEvoPayAccount">showEvoPayAccount()</option>
                                </optgroup>

                                <optgroup label="Transactions Methods">
                                    <option label="listEvoPayTransactions()" value="listEvoPayTransactions">listEvoPayTransactions()</option>
                                    <option label="showEvoPayTransaction()" value="showEvoPayTransaction">showEvoPayTransaction()</option>
                                </optgroup>

                            </select>
                            <span class="help-block" id="productionWarning">It is NOT recommended to use any of the create*() methods when using the Production environment as you will be affecting REAL data.</span>
                        </div>
                    </div>
		        </fieldset>

		        <fieldset id="listParameters" class="options">
                    <legend>List Parameters</legend>

                        <div class="control-group">
                            <label class="control-label" for="page"><code>page</code></label>
                            <div class="controls">
                                <input name="page" id="page" type="number" value="1" min="1" step="1" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="per_page"><code>per_page</code></label>
                            <div class="controls">
                                <input name="per_page" id="per_page" type="number" value="10" min="1" max="100" step="1" />
                            </div>
	                    </div>

		        </fieldset>

		        <fieldset id="methodInput" class="options">
                    <legend>Method Specific Parameters</legend>

                    <div class="control-group listBrokerages listUsers listVenues listVenuesDeleted listPerformers listPerformersDeleted listEvents listEventsDeleted listConfigurations listCategories listCategoriesDeleted listQuotes listClients createClients updateClient listClientCompanies createClientCompanies updateClientCompany listClientAddresses createClientAddresses updateClientAddress createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="name"><code>name</code></label>
                        <div class="controls">
                            <input name="name" id="name" type="text" value="" />
                                </div>
                    </div>

                    <div class="control-group listBrokerages">
                        <label class="control-label" for="abbreviation"><code>abbreviation</code></label>
                        <div class="controls">
                            <input name="abbreviation" id="abbreviation" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listBrokerages listUsers listQuotes">
                        <label class="control-label" for="email"><code>email</code></label>
                        <div class="controls">
                            <input name="email" id="email" type="email" value="" />
                        </div>
                    </div>

                    <div class="control-group listClients">
                        <label class="control-label" for="created_at"><code>created_at</code></label>
                        <div class="controls">
                            <select class="input-mini" name="created_at_operator" id="created_at_operator">
                                <option value="eq">=</option>
                                <option value="not_eq">≠</option>
                                <option value="gt">&#62;</option>
                                <option value="gte" selected="selected">≥</option>
                                <option value="lt">&#60;</option>
                                <option value="lte">≤</option>
                            </select>
                            <input name="created_at" id="created_at" class="" type="text" value="" placeholder="<?php echo date('c');?>" />
                        </div>
                    </div>

                    <div class="control-group listBrokerages listOffices listUsers listVenues listVenuesDeleted listPerformers listPerformersDeleted listEvents listEventsDeleted listConfigurations listCategories listCategoriesDeleted listTicketGroups listShipments listQuotes listOrders listClients listClientCompanies listClientPhoneNumbers listClientAddresses listClientEmailAddresses listClientCreditCards listEvoPayAccounts listEvoPayTransactions">
                        <label class="control-label" for="updated_at"><code>updated_at</code></label>
                        <div class="controls">
                            <select class="input-mini" name="updated_at_operator" id="updated_at_operator">
                                <option value="eq">=</option>
                                <option value="not_eq">≠</option>
                                <option value="gt">&#62;</option>
                                <option value="gte" selected="selected">≥</option>
                                <option value="lt">&#60;</option>
                                <option value="lte">≤</option>
                            </select>
                            <input name="updated_at" id="updated_at" class="" type="text" value="" placeholder="<?php echo date('c');?>" />
                        </div>
                    </div>

                    <div class="control-group listEvents listEventsDeleted">
                        <label class="control-label" for="occurs_at"><code>occurs_at</code></label>
                        <div class="controls">
                            <select class="input-mini" name="occurs_at_operator" id="occurs_at_operator">
                                <option value="eq">=</option>
                                <option value="not_eq">≠</option>
                                <option value="gt">&#62;</option>
                                <option value="gte" selected="selected">≥</option>
                                <option value="lt">&#60;</option>
                                <option value="lte">≤</option>
                            </select>
                            <input name="occurs_at" id="occurs_at" class="" type="text" value="" placeholder="<?php echo date('c');?>" />
                        </div>
                    </div>

                    <div class="control-group listVenuesDeleted listPerformersDeleted listEventsDeleted listCategoriesDeleted">
                        <label class="control-label" for="deleted_at"><code>deleted_at</code></label>
                        <div class="controls">
                            <select class="input-mini" name="deleted_at_operator" id="deleted_at_operator">
                                <option value="gt">&#62;</option>
                                <option value="gte" selected="selected">≥</option>
                                <option value="lt">&#60;</option>
                                <option value="lte">≤</option>
                            </select>
                            <input name="deleted_at" id="deleted_at" class="" type="text" value="" placeholder="<?php echo date('c');?>" />
                        </div>
                    </div>

                    <div class="control-group searchBrokerages searchOffices searchUsers searchVenues searchPerformers searchQuotes search">
                        <label class="control-label" for="q">Search Term (<code>q</code>)</label>
                        <div class="controls">
                            <input name="q" id="q" type="text" value="Front Row" />
                        </div>
                    </div>

                    <div class="control-group showBrokerage listOffices listUsers listClients">
                        <label class="control-label" for="brokerage_id"><code>brokerage_id</code></label>
                        <div class="controls">
                            <input name="brokerage_id" id="brokerage_id" type="text" value="32" />
                        </div>
                    </div>

                    <div class="control-group listOffices">
                        <label class="control-label" for="isMain">Is Main? (<code>main</code>)</label>
                        <div class="controls">
                            <input name="main" id="isMain" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listPerformers">
                        <label class="control-label" for="only_with_upcoming_events"><code>only_with_upcoming_events</code></label>
                        <div class="controls">
                            <input name="only_with_upcoming_events" id="only_with_upcoming_events" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group showOffice listUsers listEvents listTicketGroups listClients createClients updateClient">
                        <label class="control-label" for="office_id"><code>office_id</code></label>
                        <div class="controls">
                            <input name="office_id" id="office_id" type="text" value="223" />
                        </div>
                    </div>

                    <div class="control-group showUser listQuotes">
                        <label class="control-label" for="user_id"><code>user_id</code></label>
                        <div class="controls">
                            <input name="user_id" id="user_id" type="text" value="50" />
                        </div>
                    </div>

                    <div class="control-group showVenue listPerformers listPerformersDeleted listEvents listEventsDeleted listConfigurations">
                        <label class="control-label" for="venue_id"><code>venue_id</code></label>
                        <div class="controls">
                            <input name="venue_id" id="venue_id" type="text" value="7648" />
                        </div>
                    </div>

                    <div class="control-group showPerformer listEvents listEventsDeleted">
                        <label class="control-label" for="performer_id"><code>performer_id</code></label>
                        <div class="controls">
                            <input name="performer_id" id="performer_id" type="text" value="10638" />
                        </div>
                    </div>

                    <div class="control-group listEvents">
                        <label class="control-label" for="primary_performer"><code>primary_performer</code></label>
                        <div class="controls">
                            <input name="primary_performer" id="primary_performer" type="checkbox" value="true" />
                        </div>
                    </div>

                    <div class="control-group listEvents">
                        <label class="control-label" for="non_primary_id"><code>non_primary_id</code></label>
                        <div class="controls">
                            <input name="non_primary_id" id="non_primary_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents">
                        <label class="control-label" for="by_time"><code>by_time</code></label>
                        <div class="controls">
                        <select name="by_time" id="by_time">
                            <option value="">Select One…</option>
                            <option value="day">day</option>
                            <option value="night">night</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group search">
                        <label class="control-label" for="types"><code>types</code></label>
                        <div class="controls">
                        <select name="types[]" id="types" multiple="multiple" size="5">
                            <option value="performers">performers</option>
                            <option value="venues">venues</option>
                            <option value="offices">offices</option>
                            <option value="clients">clients</option>
                            <option value="events">events</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group search">
                        <label class="control-label" for="fuzzy"><code>fuzzy</code></label>
                        <div class="controls">
                            <input name="fuzzy" id="fuzzy" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listEvents listPerformers listVenues">
                        <label class="control-label" for="order_by1"><code>order_by</code></label>
                        <div class="controls">
                            <input name="order_by" id="order_by1" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents listVenues">
                        <label class="control-label" for="ip"><code>ip</code></label>
                        <div class="controls">
                            <input name="ip" id="ip" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents listVenues">
                        <label class="control-label" for="lat"><code>lat</code></label>
                        <div class="controls">
                            <input name="lat" id="lat" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents listVenues">
                        <label class="control-label" for="lon"><code>lon</code></label>
                        <div class="controls">
                            <input name="lon" id="lon" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents listVenues">
                        <label class="control-label" for="city_state"><code>city_state</code></label>
                        <div class="controls">
                            <input name="city_state" id="city_state" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents listVenues">
                        <label class="control-label" for="radius"><code>radius</code></label>
                        <div class="controls">
                            <input name="radius" id="radius" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvents listVenues">
                        <label class="control-label" for="postal_code1"><code>postal_code</code></label>
                        <div class="controls">
                            <input name="postal_code" id="postal_code1" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group showCategory listPerformers listEvents listEventsDeleted">
                        <label class="control-label" for="category_id"><code>category_id</code></label>
                        <div class="controls">
                            <input name="category_id" id="category_id" type="text" value="20" />
                        </div>
                    </div>

                    <div class="control-group listPerformers listVenues">
                        <label class="control-label" for="first_letter"><code>first_letter</code></label>
                        <div class="controls">
                            <input name="first_letter" id="first_letter" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group showConfiguration listEvents listEventsDeleted">
                        <label class="control-label" for="configuration_id"><code>configuration_id</code></label>
                        <div class="controls">
                            <input name="configuration_id" id="configuration_id" type="text" value="15029" />
                        </div>
                    </div>

                    <div class="control-group showEvent listTicketGroups listQuotes">
                        <label class="control-label" for="event_id"><code>event_id</code></label>
                        <div class="controls">
                            <input name="event_id" id="event_id" type="text" value="301599" />
                        </div>
                    </div>

                    <div class="control-group listCategories listCategoriesDeleted">
                        <label class="control-label" for="parent_id"><code>parent_id</code></label>
                        <div class="controls">
                            <input name="parent_id" id="parent_id" type="text" value="19" />
                        </div>
                    </div>

                    <div class="control-group showTicketGroup">
                        <label class="control-label" for="ticket_group_id"><code>ticket_group_id</code></label>
                        <div class="controls">
                            <input name="ticket_group_id" id="ticket_group_id" type="text" value="15894788" />
                        </div>
                    </div>

                    <div class="control-group showQuote">
                        <label class="control-label" for="quote_id"><code>quote_id</code></label>
                        <div class="controls">
                            <input name="quote_id" id="quote_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listConfigurations">
                        <label class="control-label" for="capacity"><code>capacity</code></label>
                        <div class="controls">
                            <input name="capacity" id="capacity" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="section"><code>section</code></label>
                        <div class="controls">
                            <input name="section" id="section" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="row"><code>row</code></label>
                        <div class="controls">
                            <input name="row" id="row" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="eticket"><code>eticket</code></label>
                        <div class="controls">
                            <input name="eticket" id="eticket" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="lightweight"><code>lightweight</code></label>
                        <div class="controls">
                            <input name="lightweight" id="lightweight" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="last_minute_tickets"><code>last_minute_tickets</code></label>
                        <div class="controls">
                            <input name="last_minute_tickets" id="last_minute_tickets" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="quantity"><code>quantity</code></label>
                        <div class="controls">
                            <input name="quantity" id="quantity" type="number" value="4" min="1" />
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="ticketgroup_type">(ticketgroup) <code>type</code></label>
                        <div class="controls">
                        <select name="type" id="ticketgroup_type">
                            <option value="">All</option>
                            <option value="event" selected="selected">event</option>
                            <option value="parking">parking</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group listTicketGroups">
                        <label class="control-label" for="price"><code>price</code> (maximum)</label>
                        <div class="controls">
                            <input name="price" id="price" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listShipments createShipments updateShipment">
                        <label class="control-label" for="tracking_number"><code>tracking_number</code></label>
                        <div class="controls">
                            <input name="tracking_number" id="tracking_number" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listShipments">
                        <label class="control-label" for="client_order_id"><code>client_order_id</code></label>
                        <div class="controls">
                            <input name="client_order_id" id="client_order_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listShipments">
                        <label class="control-label" for="partner_order_id"><code>partner_order_id</code></label>
                        <div class="controls">
                            <input name="partner_order_id" id="partner_order_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listShipments createShipments updateShipment">
                        <label class="control-label" for="shipment_type"><code>shipment_type</code></label>
                        <div class="controls">
                        <select name="shipment_type" id="shipment_type">
                            <option value="">Select One…</option>
                            <option value="FedEx">FedEx</option>
                            <option value="UPS">UPS</option>
                            <option value="Courier">Courier</option>
                            <option value="WillCall">Will Call</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group listShipments">
                        <label class="control-label" for="shipment_state">(shipment) <code>state</code></label>
                        <div class="controls">
                        <select name="state" id="shipment_state">
                            <option value="">Select One…</option>
                            <option value="pending">pending</option>
                            <option value="in_transit">in_transit</option>
                            <option value="delivered">delivered</option>
                            <option value="returned">returned</option>
                            <option value="exception">exception</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group listShipments createShipments showOrder acceptOrder rejectOrder updateOrder completeOrder listEvoPayTransactions">
                        <label class="control-label" for="order_id"><code>order_id</code></label>
                        <div class="controls">
                            <input name="order_id" id="order_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group showShipment updateShipment">
                        <label class="control-label" for="shipment_id"><code>shipment_id</code></label>
                        <div class="controls">
                            <input name="shipment_id" id="shipment_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createShipments updateShipment">
                        <label class="control-label" for="airbill"><code>airbill</code></label>
                        <div class="controls">
                            <input name="airbill" id="airbill" type="file" />
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="items"><code>items</code></label>
                        <div class="controls">
                            <input name="items" id="items" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createShipments updateShipment">
                        <label class="control-label" for="service_type1"><code>service_type</code></label>
                        <div class="controls">
                            <select name="service_type" id="service_type1">
                                <option value="FEDEX_GROUND_HOME">Ground Home Delivery</option>
                                <option value="FEDEX_GROUND">FedEx Ground</option>
                                <option value="FEDEX_FIRST_OVERN">First Overnight</option>
                                <option value="8">FedEx 2 Day Saturday Delivery</option>
                                <option value="10">International Ground</option>
                                <option value="FEDEX_INTNL_ECO">International Economy</option>
                                <option value="FEDEX_INTNL_PRI">International Priority</option>
                                <option value="FEDEX_INTNL_FIRST">International First</option>
                                <option value="14">International Priority Saturday Delivery</option>
                                <option value="FEDEX_EXPRESS_SAV">FedEx Express Saver</option>
                                <option value="PRIORITY_OVERNIGHT_SATURDAY_DELIVERY">Priority Overnight Saturday Delivery</option>
                                <option value="FEDEX_PRIORITY_OVERN">Priority Overnight</option>
                                <option value="FEDEX_2DAY">FedEx 2 Day</option>
                                <option value="FEDEX_STANDARD_OVERN">Standard Overnight</option>
                            </select>
                            <span class="help-inline">Service type for this shipment, as returned from a call to get rates.</span>
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="phone_number_attributes"><code>phone_number_attributes</code></label>
                        <div class="controls">
                            <input name="phone_number_attributes" id="phone_number_attributes" type="text" value="" disabled="disabled" />
                            <span class="help-inline">An array of information regarding the phone number.</span>
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="address_attributes"><code>address_attributes</code></label>
                        <div class="controls">
                            <input name="address_attributes" id="address_attributes" type="text" value="" disabled="disabled" />
                            <span class="help-inline">Use this if you are creating a new address.</span>
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="ship_to_name"><code>ship_to_name</code></label>
                        <div class="controls">
                            <input name="ship_to_name" id="ship_to_name" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="ship_to_company_name"><code>ship_to_company_name</code></label>
                        <div class="controls">
                            <input name="ship_to_company_name" id="ship_to_company_name" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="signature_type"><code>signature_type</code></label>
                        <div class="controls">
                        <select name="state" id="signature_type">
                            <option value="SERVICE_DEFAULT">SERVICE_DEFAULT</option>
                            <option value="NO_SIGNATURE_REQUIRED">NO_SIGNATURE_REQUIRED</option>
                            <option value="ADULT">ADULT</option>
                            <option value="DIRECT">DIRECT</option>
                            <option value="INDIRECT">INDIRECT</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group createShipments">
                        <label class="control-label" for="service_type2"><code>service_type</code></label>
                        <div class="controls">
                            <select name="service_type" id="service_type2">
                                <option value="1">Ground Home Delivery</option>
                                <option value="2">FedEx Ground</option>
                                <option value="7">First Overnight</option>
                                <option value="8">FedEx 2 Day Saturday Delivery</option>
                                <option value="10">International Ground</option>
                                <option value="11">International Economy</option>
                                <option value="12">International Priority</option>
                                <option value="13">International First</option>
                                <option value="14">International Priority Saturday Delivery</option>
                                <option value="3">FedEx Express Saver</option>
                                <option value="9">Priority Overnight Saturday Delivery</option>
                                <option value="6">Priority Overnight</option>
                                <option value="4">FedEx 2 Day</option>
                                <option value="5">Standard Overnight</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group listOrders">
                        <label class="control-label" for="buyer_id"><code>buyer_id</code></label>
                        <div class="controls">
                            <input name="buyer_id" id="buyer_id" type="text" value="" />
                            <span class="help-inline">Office ID of the seller.</span>
                        </div>
                    </div>

                    <div class="control-group listOrders createOrdersClient">
                        <label class="control-label" for="seller_id"><code>seller_id</code></label>
                        <div class="controls">
                            <input name="seller_id" id="seller_id" type="text" value="" />
                            <span class="help-inline">Office ID of the seller.</span>
                        </div>
                    </div>

                    <div class="control-group listOrders">
                        <label class="control-label" for="order_state">(order) <code>state</code></label>
                        <div class="controls">
                        <select name="state" id="order_state">
                            <option value="">Select One…</option>
                            <option value="pending">pending</option>
                            <option value="accepted">accepted</option>
                            <option value="rejected">rejected</option>
                            <option value="cancelled">cancelled</option>
                            <option value="expired">expired</option>
                            <option value="pending_substitution">pending_substitution</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group listOrders createOrdersClient updateOrder">
                        <label class="control-label" for="po_number"><code>po_number</code></label>
                        <div class="controls">
                            <input name="po_number" id="po_number" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listOrders createOrdersClient updateOrder">
                        <label class="control-label" for="invoice_number"><code>invoice_number</code></label>
                        <div class="controls">
                            <input name="invoice_number" id="invoice_number" type="text" value="" />
                        </div>
                    </div>

                    <fieldset class="createOrdersClient clientOrderParameters">
                        <legend>Item 1</legend>

                        <div class="control-group createOrdersClient">
                            <label class="control-label" for="item_1_ticket_group_id"><code>ticket_group_id</code></label>
                            <div class="controls">
                                <input name="items[0][ticket_group_id]" id="item_1_ticket_group_id" type="text" value="15894788" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient">
                            <label class="control-label" for="item_1_quantity"><code>quantity</code></label>
                            <div class="controls">
                                <input name="items[0][quantity]" id="item_1_quantity" type="text" value="2" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient">
                            <label class="control-label" for="item_1_price"><code>price</code></label>
                            <div class="controls">
                                <input name="items[0][price]" id="item_1_price" type="text" value="1295.00" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="createOrdersClient clientOrderParameters">
                        <legend>Item 2 (optional)</legend>

                        <div class="control-group createOrdersClient">
                            <label class="control-label" for="item_2_ticket_group_id"><code>ticket_group_id</code></label>
                            <div class="controls">
                                <input name="items[1][ticket_group_id]" id="item_2_ticket_group_id" type="text" value="15894807" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient">
                            <label class="control-label" for="item_2_quantity"><code>quantity</code></label>
                            <div class="controls">
                                <input name="items[1][quantity]" id="item_2_quantity" type="text" value="2" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient">
                            <label class="control-label" for="item_2_price"><code>price</code></label>
                            <div class="controls">
                                <input name="items[1][price]" id="item_2_price" type="text" value="1272.99" />
                            </div>
                        </div>
                    </fieldset>


                    <div class="control-group createOrdersClient">
                        <label class="control-label" for="payments"><code>payments</code></label>
                        <div class="controls">
                            <select name="payments" id="payments">
                                <option value="offline">offline</option>
                                <option value="credit_card">credit_card</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group createOrdersClient updateOrder">
                        <label class="control-label" for="shipping_address_id"><code>shipping_address_id</code></label>
                        <div class="controls">
                            <input name="shipping_address_id" id="shipping_address_id" type="text" value="" />
                        </div>
                    </div>

                    <fieldset class="control-group updateOrder clientOrderParameters" id="client_order_shipping_address">
                        <legend>Shipping Address</legend>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_label"><code>label</code></label>
                            <div class="controls">
                                <input name="shipping_address[label]" id="shipping_address_label" type="text" value="Work" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_name"><code>name</code></label>
                            <div class="controls">
                                <input name="shipping_address[name]" id="shipping_address_name" type="text" value="Moe Szyslak" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_company"><code>company</code></label>
                            <div class="controls">
                                <input name="shipping_address[company]" id="shipping_address_company" type="text" value="Moe’s Tavern" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_street_address"><code>street_address</code></label>
                            <div class="controls">
                                <input name="shipping_address[street_address]" id="shipping_address_street_address" type="text" value="555 Evergreen Terrace" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_extended_address"><code>extended_address</code></label>
                            <div class="controls">
                                <input name="shipping_address[extended_address]" id="shipping_address_extended_address" type="text" value="Suite 666" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_locality"><code>locality</code></label>
                            <div class="controls">
                                <input name="shipping_address[locality]" id="shipping_address_locality" type="text" value="Springfield" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_region"><code>region</code></label>
                            <div class="controls">
                                <input name="shipping_address[region]" id="shipping_address_region" type="text" value="MG" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_postal_code"><code>postal_code</code></label>
                            <div class="controls">
                                <input name="shipping_address[postal_code]" id="shipping_address_postal_code" type="text" value="58008-0000" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="shipping_address_country_code"><code>country_code</code></label>
                            <div class="controls">
                                <input name="shipping_address[country_code]" id="shipping_address_country_code" type="text" value="US" />
                            </div>
                        </div>

                    </fieldset>


                    <div class="control-group createOrdersClient updateOrder">
                        <label class="control-label" for="billing_address_id"><code>billing_address_id</code></label>
                        <div class="controls">
                            <input name="billing_address_id" id="billing_address_id" type="text" value="" />
                        </div>
                    </div>

                    <fieldset class="control-group updateOrder clientOrderParameters" id="client_order_billing_address">
                        <legend>Billing Address</legend>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_label"><code>label</code></label>
                            <div class="controls">
                                <input name="billing_address[label]" id="billing_address_label" type="text" value="Home" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_name"><code>name</code></label>
                            <div class="controls">
                                <input name="billing_address[name]" id="billing_address_name" type="text" value="Ned Flanders" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_company"><code>company</code></label>
                            <div class="controls">
                                <input name="billing_address[company]" id="billing_address_company" type="text" value="" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_street_address"><code>street_address</code></label>
                            <div class="controls">
                                <input name="billing_address[street_address]" id="billing_address_street_address" type="text" value="744 Evergreen Terrace" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_extended_address"><code>extended_address</code></label>
                            <div class="controls">
                                <input name="billing_address[extended_address]" id="billing_address_extended_address" type="text" value="" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_locality"><code>locality</code></label>
                            <div class="controls">
                                <input name="billing_address[locality]" id="billing_address_locality" type="text" value="Springfield" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_region"><code>region</code></label>
                            <div class="controls">
                                <input name="billing_address[region]" id="billing_address_region" type="text" value="MG" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_postal_code"><code>postal_code</code></label>
                            <div class="controls">
                                <input name="billing_address[postal_code]" id="billing_address_postal_code" type="text" value="58008-0000" />
                            </div>
                        </div>

                        <div class="control-group createOrdersClient updateOrder">
                            <label class="control-label" for="billing_address_country_code"><code>country_code</code></label>
                            <div class="controls">
                                <input name="billing_address[country_code]" id="billing_address_country_code" type="text" value="US" />
                            </div>
                        </div>

                    </fieldset>


                    <div class="control-group createOrdersClient">
                        <label class="control-label" for="shipping"><code>shipping</code></label>
                        <div class="controls">
                            <input name="shipping" id="shipping" type="text" value="12.95" />
                        </div>
                    </div>

                    <div class="control-group createOrdersClient">
                        <label class="control-label" for="service_fee"><code>service_fee</code></label>
                        <div class="controls">
                            <input name="service_fee" id="service_fee" type="text" value="22.50" />
                        </div>
                    </div>

                    <div class="control-group createOrdersClient">
                        <label class="control-label" for="tax"><code>tax</code></label>
                        <div class="controls">
                            <input name="tax" id="tax" type="text" value="0.00" />
                        </div>
                    </div>

                    <div class="control-group createOrdersClient">
                        <label class="control-label" for="additional_expense"><code>additional_expense</code></label>
                        <div class="controls">
                            <input name="additional_expense" id="additional_expense" type="text" value="0.00" />
                        </div>
                    </div>

                    <div class="control-group createOrdersClient updateOrder">
                        <label class="control-label" for="instructions"><code>instructions</code></label>
                        <div class="controls">
                        <textarea name="instructions" id="instructions"></textarea>
                        </div>
                    </div>

                    <div class="control-group acceptOrder rejectOrder">
                        <label class="control-label" for="reviewer_id"><code>reviewer_id</code></label>
                        <div class="controls">
                            <input name="reviewer_id" id="reviewer_id" type="text" value="" />
                            <span class="help-inline">The user ID of the reviewer who belongs to the brokerage who received the order.</span>
                        </div>
                    </div>

                    <div class="control-group rejectOrder">
                        <label class="control-label" for="rejection_reason"><code>rejection_reason</code></label>
                        <div class="controls">
                            <select name="rejection_reason" id="rejection_reason">
                                <option label="Tickets No Longer Available" value="Tickets No Longer Available">Tickets No Longer Available</option>
                                <option label="Tickets Priced Incorrectly" value="Tickets Priced Incorrectly">Tickets Priced Incorrectly</option>
                                <option label="Duplicate Order" value="Duplicate Order">Duplicate Order</option>
                                <option label="Fraudulent Order" value="Fraudulent Order">Fraudulent Order</option>
                                <option label="This Reason Is Invalid" value="This Reason Is Invalid">This Reason Is Invalid</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group showClient updateClient listClientPhoneNumbers showClientPhoneNumber createClientPhoneNumbers updateClientPhoneNumber listClientEmailAddresses showClientEmailAddress createClientEmailAddresses updateClientEmailAddress listClientAddresses showClientAddress createClientAddresses updateClientAddress createClientCreditCards listClientCreditCards showClientCreditCard updateClientCreditCard createOrdersClient">
                        <label class="control-label" for="client_id"><code>client_id</code></label>
                        <div class="controls">
                            <input name="client_id" id="client_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group showClientCompany updateClientCompany listClients">
                        <label class="control-label" for="company_id"><code>company_id</code></label>
                        <div class="controls">
                            <input name="company_id" id="company_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group showClientPhoneNumber updateClientPhoneNumber createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="phone_number_id"><code>phone_number_id</code></label>
                        <div class="controls">
                            <input name="phone_number_id" id="phone_number_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientPhoneNumbers createClientPhoneNumbers updateClientPhoneNumber listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="country_code"><code>country_code</code></label>
                        <div class="controls">
                            <input name="country_code" id="country_code" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientPhoneNumbers createClientPhoneNumbers updateClientPhoneNumber createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="number"><code>number</code></label>
                        <div class="controls">
                            <input name="number" id="number" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientPhoneNumbers createClientPhoneNumbers updateClientPhoneNumber">
                        <label class="control-label" for="extension"><code>extension</code></label>
                        <div class="controls">
                            <input name="extension" id="extension" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientPhoneNumbers createClientPhoneNumbers updateClientPhoneNumber listClientEmailAddresses createClientEmailAddresses updateClientEmailAddress listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="label"><code>label</code></label>
                        <div class="controls">
                            <input name="label" id="label" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientEmailAddresses createClientEmailAddresses updateClientEmailAddress">
                        <label class="control-label" for="address">(email) <code>address</code></label>
                        <div class="controls">
                            <input name="address" id="address" type="email" value="" />
                        </div>
                    </div>

                    <div class="control-group showClientAddress updateClientAddress createClientCreditCards updateClientCreditCard createShipments">
                        <label class="control-label" for="address_id"><code>address_id</code></label>
                        <div class="controls">
                            <input name="address_id" id="address_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group showClientEmailAddress updateClientEmailAddress">
                        <label class="control-label" for="email_address_id"><code>email_address_id</code></label>
                        <div class="controls">
                            <input name="email_address_id" id="email_address_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group updateClient">
                        <label class="control-label" for="primary_shipping_address_id"><code>primary_shipping_address_id</code></label>
                        <div class="controls">
                            <input name="primary_shipping_address_id" id="primary_shipping_address_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="company"><code>company</code></label>
                        <div class="controls">
                            <input name="company" id="company" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="street_address"><code>street_address</code></label>
                        <div class="controls">
                            <input name="street_address" id="street_address" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="extended_address"><code>extended_address</code></label>
                        <div class="controls">
                            <input name="extended_address" id="extended_address" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="locality"><code>locality</code></label>
                        <div class="controls">
                            <input name="locality" id="locality" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="region"><code>region</code></label>
                        <div class="controls">
                            <input name="region" id="region" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="postal_code2"><code>postal_code</code></label>
                        <div class="controls">
                            <input name="postal_code" id="postal_code2" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listClientAddresses createClientAddresses updateClientAddress">
                        <label class="control-label" for="isPrimary">Is primary? (<code>primary</code>)</label>
                        <div class="controls">
                            <input name="primary" id="isPrimary" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listEvents">
                        <label class="control-label" for="unique_performers"><code>unique_performers</code></label>
                        <div class="controls">
                            <input name="unique_performers" id="unique_performers" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group showClientCreditCard updateClientCreditCard">
                        <label class="control-label" for="credit_card_id"><code>credit_card_id</code></label>
                        <div class="controls">
                            <input name="credit_card_id" id="credit_card_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group updateClient">
                        <label class="control-label" for="primary_credit_card_id"><code>primary_credit_card_id</code></label>
                        <div class="controls">
                            <input name="primary_credit_card_id" id="primary_credit_card_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="expiration_month"><code>expiration_month</code></label>
                        <div class="controls">
                            <input name="expiration_month" id="expiration_month" type="text" value="" pattern="\d{2}" />
                        </div>
                    </div>

                    <div class="control-group createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="expiration_year"><code>expiration_year</code></label>
                        <div class="controls">
                            <input name="expiration_year" id="expiration_year" type="text" value="" pattern="\d{4}" />
                        </div>
                    </div>

                    <div class="control-group createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="ip_address"><code>ip_address</code></label>
                        <div class="controls">
                            <input name="ip_address" id="ip_address" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createClientCreditCards updateClientCreditCard">
                        <label class="control-label" for="verification_code"><code>verification_code</code></label>
                        <div class="controls">
                            <input name="verification_code" id="verification_code" type="text" value="" pattern="\d{3,4}" />
                        </div>
                    </div>

                    <div class="control-group listEvoPayAccounts">
                        <label class="control-label" for="balance"><code>balance</code></label>
                        <div class="controls">
                            <input name="balance" id="balance" type="text" value="" placeholder="1080.00" pattern="\d+\.\d{2}" />
                            <span class="help-inline">Account balance without currency symbol. e.g. “100.00”.</span>
                        </div>
                    </div>

                    <div class="control-group listEvoPayAccounts">
                        <label class="control-label" for="currency"><code>currency</code></label>
                        <div class="controls">
                            <input name="currency" id="currency" type="text" value="" placeholder="USD" />
                        </div>
                    </div>

                    <div class="control-group showEvoPayAccount listEvoPayTransactions showEvoPayTransaction">
                        <label class="control-label" for="account_id">(EvoPay) <code>account_id</code></label>
                        <div class="controls">
                            <input name="account_id" id="account_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listEvoPayTransactions">
                        <label class="control-label" for="amount"><code>amount</code></label>
                        <div class="controls">
                            <input name="amount" id="amount" type="text" value="" placeholder="1080.00" pattern="\d+\.\d{2}" />
                            <span class="help-inline">Amount of transaction without currency symbol. e.g. “100.00”.</span>
                        </div>
                    </div>

                    <div class="control-group listEvoPayTransactions">
                        <label class="control-label" for="evopay_transaction_type">(EvoPay Transaction) <code>type</code></label>
                        <div class="controls">
                        <select name="type" id="evopay_transaction_type">
                            <option value="">Select One…</option>
                            <option value="Credit">Credit</option>
                            <option value="etc">etc</option>
                        </select>
                        </div>
                    </div>

                    <div class="control-group showEvoPayTransaction">
                        <label class="control-label" for="transaction_id">(EvoPay) <code>transaction_id</code></label>
                        <div class="controls">
                            <input name="transaction_id" id="transaction_id" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group listSettingsShipping">
                        <label class="control-label" for="show_on_pos"><code>show_on_pos</code></label>
                        <div class="controls">
                            <input name="show_on_pos" id="show_on_pos" type="checkbox" value="1" />
                        </div>
                    </div>

                    <div class="control-group listSettingsShipping">
                        <label class="control-label" for="show_on_site"><code>show_on_site</code></label>
                        <div class="controls">
                            <input name="show_on_site" id="show_on_site" type="checkbox" value="1" />
                        </div>
                    </div>



                    <div class="control-group listEvents">
                        <label class="control-label" for="order_by2"><code>order_by</code></label>
                        <div class="controls">
                            <input name="order_by" id="order_by2" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group createClients updateClient" id="tagsWrapper">
                        <label class="control-label" for="tags"><code>tags</code></label>
                        <div class="controls">
                            <input class="tagManager" name="tags" id="tags" type="text" value="" />
                        </div>
                    </div>

		        </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large disabled">Submit</button>
                </div>

		    </form>
		</div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
        <script src="/js/demo.js"></script>
        <script src="/js/bootstrap-tagmanager.js"></script>

    </body>
</html>

<?php

/**
 * Utility function for putting the submitted options into an array
 *
 * @param Zend_Filter_Input $input
 * @return array
 */
function _getOptions($input)
{
    $options = array();

    $dateFields = array(
        'updated_at',
        'occurs_at',
        'deleted_at',
        'created_at',
    );

    /**
     * We get the unknown $input variables because we
     * don't want to have to specify each one in the
     * $validators for this demo.
     */
    $unknown = $input->getUnknown();
    foreach ($unknown as $key => $value) {
        if ($value !== '' && stristr($key, '_operator') === false) {
//        if (stristr($key, '_operator') === false) {
            if (in_array($key, $dateFields)) {
                $operatorKey = $key . '_operator';
                $options[$key . '.' . $unknown[$operatorKey]] = $value;
            } else {
                $options[$key] = $value;
            }
        }
    }

    return $options;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param int $showId
 */
function _outputShowCode($libraryMethod, $showId)
{
    echo '$results = $tevo->' . $libraryMethod . '(' . $showId . ');' . PHP_EOL;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param int $itemId
 * @param int $showId
 */
function _outputShowByIdCode($libraryMethod, $itemId, $showId)
{
    echo '$results = $tevo->' . $libraryMethod . '(' . $itemId . ', ' . $showId . ');' . PHP_EOL;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param array $options
 */
function _outputOptionsCode($options)
{
    echo '$options = array(' . PHP_EOL;
    foreach( $options as $key => $val) {
        if (!is_array($val) && !is_object($val) && is_numeric($val)) {
            echo '    \'' . $key . '\' => ' . $val . ',' . PHP_EOL;
        } elseif (!is_array($val) && !is_object($val) && !is_numeric($val)) {
            echo '    \'' . $key . '\' => \'' . $val . '\',' . PHP_EOL;
//         } elseif (is_array($val)) {
//             echo '    \'' . $key . '\' => \'' . implode(',', $val) . '\',' . PHP_EOL;
        }
    }
    echo ');' . PHP_EOL . PHP_EOL
    ;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param array $options
 */
function _outputListCode($libraryMethod, $options)
{
    _outputOptionsCode($options);

    echo '$results = $tevo->' . $libraryMethod . '($options);' . PHP_EOL;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param int $listId
 * @param array $options
 */
function _outputListByIdCode($libraryMethod, $listId, $options)
{
    echo '$options = array(' . PHP_EOL;
    foreach( $options as $key => $val) {
        echo '    \'' . $key . '\' => ' . $val . ',' . PHP_EOL;
    }
    echo ');' . PHP_EOL
       . PHP_EOL
       . '$results = $tevo->' . $libraryMethod . '(' . $listId . ', $options);' . PHP_EOL
    ;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param string $queryString
 * @param array $options
 */
function _outputSearchCode($libraryMethod, $queryString, $options)
{
    _outputOptionsCode($options);

    echo '$results = $tevo->' . $libraryMethod . '(\'' . $queryString . '\', $options);' . PHP_EOL;
}


/**
 * Utility function for performing show*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param int $showId
 * @return stdClass
 */
function _doShow(\TicketEvolution\Webservice $tevo, $libraryMethod, $showId)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($showId);
    } catch (Exception $e) {
        echo '</pre>' . PHP_EOL
           . '<h1>Exception thrown trying to perform API request</h1>' . PHP_EOL
           . _getRequest($tevo, $libraryMethod, true)
           . _getResponse($tevo, $libraryMethod, true);
        exit (1);
    }

    return $results;
}


/**
 * Utility function for performing show*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param int $itemId
 * @param int $showId
 * @return stdClass
 */
function _doShowById(\TicketEvolution\Webservice $tevo, $libraryMethod, $itemId, $showId)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($itemId, $showId);
    } catch (Exception $e) {
        echo '</pre>' . PHP_EOL
           . '<h1>Exception thrown trying to perform API request</h1>' . PHP_EOL
           . _getRequest($tevo, $libraryMethod, true)
           . _getResponse($tevo, $libraryMethod, true);
        exit (1);
    }

    return $results;
}


/**
 * Utility function for performing list*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param array $options
 * @return stdClass
 */
function _doList(\TicketEvolution\Webservice $tevo, $libraryMethod, array $options)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($options);
        return $results;
    } catch (Exception $e) {
        return false;
    }
}


/**
 * Utility function for performing list*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param int $listId
 * @param array $options
 * @return stdClass
 */
function _doListById(\TicketEvolution\Webservice $tevo, $libraryMethod, $listId, array $options)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($listId, $options);
        return $results;
    } catch (Exception $e) {
        return false;
    }
}


/**
 * Utility function for performing search*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param string $queryString
 * @param array $options
 * @return stdClass
 */
function _doSearch(\TicketEvolution\Webservice $tevo, $libraryMethod, $queryString, $options)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($queryString, $options);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing create*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param stdClass $properties
 * @return stdClass
 */
function _doCreate(\TicketEvolution\Webservice $tevo, $libraryMethod, $properties)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing create*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param int $itemId
 * @param array $properties
 * @return stdClass
 */
function _doCreateById(\TicketEvolution\Webservice $tevo, $libraryMethod, $itemId, array $properties)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($itemId, $properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing update*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param int $updateId
 * @param stdClass $properties
 * @return stdClass
 */
function _doUpdate(\TicketEvolution\Webservice $tevo, $libraryMethod, $updateId, $properties)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($updateId, $properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing update*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param int $itemId
 * @param int $updateId
 * @param array $properties
 * @return stdClass
 */
function _doUpdateById(\TicketEvolution\Webservice $tevo, $libraryMethod, $itemId, $updateId, $properties)
{
    // Execute the call
    try {
        $results = $tevo->$libraryMethod($itemId, $updateId, $properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing update*() calls
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param mixed $param1
 * @param mixed $param2
 * @param mixed $param3
 * @return stdClass
 */
function _doOther(\TicketEvolution\Webservice $tevo, $libraryMethod, $param1, $param2=null, $param3=null)
{
    // Execute the call
    try {
        if (!is_null($param3)) {
            $results = $tevo->$libraryMethod($param1, $param2, $param3);
        } elseif (!is_null($param2)) {
            $results = $tevo->$libraryMethod($param1, $param2);
        } else {
            $results = $tevo->$libraryMethod($param1);
        }
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for returning formatted API request info
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param bool $isException
 * @return string
 */
function _getRequest($tevo, $libraryMethod, $isException=true)
{
    $class = ($isException) ? 'class="exception" ' : '';
    $html = '<h2>Actual request for ' . $libraryMethod . '() method</h2>' . PHP_EOL
          . '<pre ' . $class . '>' . PHP_EOL
          . print_r ($tevo->getRestClient()->getHttpClient()->getLastRequest(), true)
          . '</pre><br />' . PHP_EOL;

    return $html;
}


/**
 * Utility function for returning formatted API response info
 *
 * @param \TicketEvolution\Webservice $tevo
 * @param string $libraryMethod
 * @param bool $isException
 * @return string
 */
function _getResponse($tevo, $libraryMethod, $isException=true)
{
    $class = ($isException) ? 'class="exception" ' : '';
    $html = '<h2>Actual response for ' . $libraryMethod . '() method</h2>' . PHP_EOL
          . '<pre ' . $class . '>' . PHP_EOL
          . print_r ($tevo->getRestClient()->getHttpClient()->getLastResponse(), true)
          . '</pre><br />' . PHP_EOL;

    return $html;
}
