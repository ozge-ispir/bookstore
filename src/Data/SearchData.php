<?php
namespace App\Data;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;

class SearchData {


    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var Author[]
     */
    public $authors = [];



}
