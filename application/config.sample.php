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
 * Set your Ticket Evolution API information.
 * This is available from your account under Brokerage->API Keys
 *
 * NOTE: These are exclusive to your company and should NEVER be shared with
 *       anyone else. These should be protected just like your bank password.
 *
 * @link https://settings.sandbox.ticketevolution.com/brokerage/credentials Sandbox Credentials
 * @link https://settings.staging.ticketevolution.com/brokerage/credentials Staging Credentials
 * @link https://settings.ticketevolution.com/brokerage/credentials Production Credentials
 */
$sandbox['apiToken']        = (string) 'YOUR_API_TOKEN_HERE';
$sandbox['secretKey']       = (string) 'YOUR_SECRET_KEY_HERE';
$sandbox['buyerId']         = 'YOUR_OFFICEID_HERE';
$sandbox['usePersistentConnections'] = true;

$staging['apiToken']        = (string) 'YOUR_API_TOKEN_HERE';
$staging['secretKey']       = (string) 'YOUR_SECRET_KEY_HERE';
$staging['buyerId']         = 'YOUR_OFFICEID_HERE';
$staging['usePersistentConnections'] = true;

$production['apiToken']     = (string) 'YOUR_API_TOKEN_HERE';
$production['secretKey']    = (string) 'YOUR_SECRET_KEY_HERE';
$production['buyerId']      = 'YOUR_OFFICEID_HERE';
$production['usePersistentConnections'] = true;

$cfg['exclude']['brokerage'] = array(
    389, // Testing only
    691, // Testing only
    117, // Testing only
);
$cfg['exclusive']['brokerage'] = array(
    223, // Testing only
    154, // Testing only
);
