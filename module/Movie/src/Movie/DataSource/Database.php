<?php

namespace Movie\DataSource;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select as SqlSelect;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Movie\DataSource\DataSourceInterface;
use Movie\Model\Movie as MovieModel;

class Database extends AbstractTableGateway implements DataSourceInterface
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

    public function findAllOwnedByUser($user_id)
    {
        $select = new SqlSelect($this->table);
        $select
            ->columns(array('*'))
            ->join(array('umc'=>'user_movie_collection'), 'umc.movie_id = movie.movie_id')
            ->where(array('umc.user_id = ?'=>$user_id));
        return $this->selectWith($select);
    }

    public function getMovie($movie_id)
    {
        $movie_id  = (int) $movie_id;
        $rowset = $this->select(array('movie_id' => $movie_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $movie_id");
        }
        return $row;
    }

    public function saveMovie(MovieModel $movie)
    {
        $data = array(
            'title'  => $movie->title,
        );

        $id = (int)$movie->movie_id;
        if ($id == MovieModel::NULL_MOVIE_ID) {
            $data['created_at'] = date('Y-m-d');
            $this->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $this->update($data, array('movie_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteMovie($movie_id)
    {
        $this->delete(array('movie_id' => $movie_id));
    }

    public function findBy($keyword)
    {

    }

}
