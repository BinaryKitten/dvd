<?php

namespace Movie\Model;

use Movie\DataSourceInterface;
use Movie\Model\Movie as MovieModel;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class MovieTable extends AbstractTableGateway implements DataSourceInterface
{
    protected $table = 'movie';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new MovieModel());

        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function findAllOwnedByUser($userId)
    {
        $select = new \Zend\Db\Sql\Select($this->table);
        $select
            ->columns(array('*'))
            ->join(array('umc'=>'user_movie_collection'), 'umc.movie_id = movie.movie_id')
            ->where(array('umc.user_id = ?'=>$userId));
        return $this->selectWith($select);
    }

    public function getMovie($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveMovie(MovieModel $movie)
    {
        $data = array(
            'title'  => $movie->title,
        );

        $id = (int)$movie->id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d');
            $this->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAlbum($id)
    {
        $this->delete(array('id' => $id));
    }

}
