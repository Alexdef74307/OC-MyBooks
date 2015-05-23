<?php
namespace MyBooks\DAO;

use MyBooks\Domain\Author;
use MyBooks\Exception\EntityNotFoundException;


class AuthorDAO extends DAO
{
    /**
     * Returns an author matchind the supplied id.
     *
     * @param integer $id The author's id.
     * @return \MyBooks\Domain\Author
     * @throws \MyBooks\Exception\EntityNotFoundException
     */
    public function find($id)
    {
        $row = $this->getDb()->fetchAssoc(
            'SELECT * '.
            'FROM author '.
            'WHERE auth_id = ?',
            array($id)
        );
        if($row)
            return $this->buildDomainObject($row);
        else
            throw new EntityNotFoundException(sprintf('No author matching id #%u.', $id));
    }
    /**
     * Creates an author object from a SQL result row.
     *
     * @param array $row SQL result row.
     * @return \MyBooks\Domain\Author
     */
    protected function buildDomainObject($row)
    {
        $author = new Author();
        $author->setId($row['auth_id']);
        $author->setFirstName($row['auth_first_name']);
        $author->setLastName($row['auth_last_name']);
        return $author;
    }
}