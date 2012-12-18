<?php

namespace Movie\DataSource;

/**
 *
 * @author k.reeve
 */
interface DataSourceInterface {
    public function fetchAll();
    public function getMovie($id);
    public function findBy($keyword);
}