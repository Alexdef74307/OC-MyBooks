<?php
namespace MyBooks\DAO;

use Doctrine\DBAL\Connection;
use MyBooks\DAO\AuthorDAO;
use MyBooks\Domain\Book;
use MyBooks\Exception\EntityNotFoundException;



class BookDAO extends DAO
{
	
	/**
	*	Author DAO.
	*
	* @var \MyBooks\DAO\AuthorDAO
	*/
	protected $authorDAO;
	
	
	public function __construct(Connection $db, AuthorDAO $authorDAO){
		parent::__construct($db);
		$this->authorDAO=$authorDAO;
	}
	
	
	/**
     * 
     * 
     * @param \MyBooks\DAO\AuthorDAO $authorDAO
     * @return \MyBooks\DAO\BookDAO
     */
    public function setAuthorDAO(AuthorDAO $authorDAO)
    {
        $this->authorDAO = $authorDAO;
        return $this;
    }


	
	
	
	/**
     * Return a list of all books, sorted by date (most recent first).
     *
     * @return array A list of all books.
     */
	public function findAll() {
		$sql = "select * from book order by book_id desc";
		$result = $this->getDb()->fetchAll($sql);

		// Convert query result to an array of domain objects
		$books = array();
		foreach ($result as $row) {
			$bookId = $row['book_id'];
			$books[$bookId] = $this->buildDomainObject($row);
		}
		return $books;
	}
	
	/**
     * Creates a Book object based on a DB row.
     *
     * @param array $row The DB row containing Book data.
     * @return \OC-MyBooks\Domain\Book
     */
    protected function buildDomainObject($row) {
        $book = new Book();
        $book->setId($row['book_id']);
        $book->setTitle($row['book_title']);
        $book->setIsbn($row['book_isbn']);
        $book->setSummary($row['book_summary']);
        		
        if(array_key_exists('auth_id', $row))
            $book->setAuthor($this->authorDAO->find($row['auth_id']));
        
            
            return $book;
        
    }
    
    /**
     * Returns a book matching the supplied id.
     *
     * @param integer $id The book id.
     * @return \MyBooks\Domain\Book|throws an exception if no matching book is found.
     */
    public function find($id)
    {
        $row = $this->getDb()->fetchAssoc(
            'SELECT * '.
            'FROM book '.
            'WHERE book_id = ?',
            array($id)
        );
        
        if($row)
            return $this->buildDomainObject($row);
        else
            throw new EntityNotFoundException(sprintf('No book matching id #%u.', $id));
    }
	
}
	
	
	
	