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
    private $barcode;

    private $checked;

    private $safe;

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
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * @param mixed $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
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


}