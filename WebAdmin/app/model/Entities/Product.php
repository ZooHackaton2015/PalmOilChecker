<?php

namespace App\Model\Entities;

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 16:58
 */
class Product extends BaseEntity
{
    public $barcode;

    public $approver_id;

    public $safe;

    public $timestamp;

    /**
     * Product constructor.
     * @param $barcode
     * @param $approver_id
     * @param $safe
     * @param $timestamp
     */
    public function __construct($barcode = '', $approver_id = 0, $safe = false, $timestamp = null)
    {
        parent::__construct();
        $this->barcode = $barcode;
        $this->approver_id = $approver_id;
        $this->safe = $safe;
        $this->timestamp = $timestamp;
    }


    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param mixed $barcode
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return mixed
     */
    public function getApproverid()
    {
        return $this->approver_id;
    }

    /**
     * @param mixed $approver_id
     */
    public function setApproverid($approver_id)
    {
        $this->approver_id = $approver_id;
    }

    /**
     * @return mixed
     */
    public function getSafe()
    {
        return $this->safe;
    }

    /**
     * @param mixed $safe
     */
    public function setSafe($safe)
    {
        $this->safe = $safe;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }



}