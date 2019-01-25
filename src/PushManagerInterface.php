<?php

namespace coderovich\pushover;

use coderovich\pushover\Model\PushInterface;

/**
 * PushManager interface.
 *
 *
 */
interface PushManagerInterface
{
    /**
     * Push message.
     *
     * @param PushInterface $push     Push
     * @param boolean       $realSend Real send
     * 
     * @return stdClass|array
     */
    public function push(PushInterface $push, $realSend = true);
}