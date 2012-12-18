<?php

namespace Movie\DataSource;

/**
 *
 * @author k.reeve
 */
interface Iface {
    public function fetchAll();
    public function getMovie($id);
    public function findBy($keyword);
}