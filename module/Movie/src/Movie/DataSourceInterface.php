<?php

namespace Movie;

/**
 *
 * @author k.reeve
 */
interface DataSourceInterface {
    public function fetchAll();
    public function getMovie($id);
}