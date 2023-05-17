<?php

//Module-8
class TelegraphText
{
    public string $text, $title, $author, $published, $slug;

    /*
     * @param string $author
     * @param string $slug
     *
     * @return void
     */
    //
    public function __construct(string $author, string $slug)
    {
        $this->published = date('l jS \of F Y h:i:s A');
        $this->author = $author;
        $this->slug = $slug;
    }

    /*
     * @return void
     */
    public function storeText(): void
    {
        $arrStoreText = [
            'text' =>  $this->text,
            'title' => $this->title,
            'author' => $this->author,
            'published' => $this->published
        ];

    }

    /*
     * @return string
     */
    public function loadText(): string {
        if (!file_exists($this->slug) || filesize($this->slug) <= 0)
        {
            return '';
        }
        $arrLoadText = unserialize(file_get_contents($this->slug));
        $this->text = $arrLoadText['text'];
        $this->title = $arrLoadText['title'];
        $this->author = $arrLoadText['author'];
        $this->published = $arrLoadText['published'];
        return $this->text;
    }

    /*
     * @param string $title
     * @param string $text
     *
     * @return void
     */
    public function editText(string $title, string $text): void
    {
        $this->text = $text;
        $this->title = $title;
    }
}


//Module-9

abstract class Storage
{
    /*
     * @param object $telegraphText
     * @param string $slug
     * @return string $id
     */
    abstract function create (object $findings, string $slug): string;
    /*
     * @param string $slug
     * @param string $id
     *
     * @return object
     */
    abstract function reade (object $findings, string $slug, string $id): object;
    /*
    * @param object $telegraphText
    * @param string $slug
    * @param string $id
    *
    * @return void
    */
    abstract function update (string $slug = '', string $id = ''): void;
    /*
     * @param string $slug
     * @param string $id
     *
     * @return void
     */
    abstract function delete (string $slug = '', string $id = ''): void;
    /*
      * @return array
      */
    abstract function list(): array;
}

abstract class View
{
    /*
     * object $storage
     */
    public $storage;

    public function __construct(string $storage)
    {
        $this->storage = $storage;
    }
    /*
       * @param string $id
       *
       * @return void
       */
    abstract function displayTextById(int $id): void;
    /*
       * @param string $url
       *
       * @return void
       */
    abstract function displayTextByUrl(string $url): void;
}

abstract class User
{
    /*
    * string $id
    * string $name
    * string role
    */
    public string
        $id,
        $name,
        $role;
    /*
     * @return array
     */
    abstract function getTextsToEdit(): array;
}

class FileStorage extends Storage
{
    public function create(object $findings, string $slug): string
    {
        $id=0;
        $numberSlug = 0;
        $slug = $slug2 = $slug . '-' . date('d-m-Y');

        while (file_exists($slug))
        {
            $numberSlug++;
            $slug = $slug2 . $numberSlug;
        }
        $findings->slug = $slug;
        file_put_contents($slug, serialize($findings));
        return $id;
    }

    function reade(object $findings, string $slug, string $id): object
    {



        return $findings;
    }

    function update(string $slug = '', string $id = ''): void
    {
        // TODO: Implement update() method.
    }

    function delete(string $slug = '', string $id = ''): void
    {
        // TODO: Implement delete() method.
    }

    function list(): array
    {
        $arrayTelegraph = [];


        return $arrayTelegraph;

    }
}
$author = 'Автор';
$slug = 'test_text_file';

$objectTelegraphText = new TelegraphText($author, $slug);
$objectTelegraphText->text = 'текст';
$objectTelegraphText->title = 'заголовок';

$objectFileStore = new FileStorage();
$objectFileStore->create($objectTelegraphText,$slug);
