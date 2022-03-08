<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

/**
 * @author Manuel Aguirre
 */
enum EmailLogStatus: string
{
    case pending = 'pending';
    case send = 'send';
    case error = 'error';
    case no_template = 'no_template';
}