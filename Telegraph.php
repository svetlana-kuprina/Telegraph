<?php

//Module-8
class TelegraphText
{
    public string $text, $title, $author, $published, $slug;


    /**
     * @param string $author
     * @param string $slug
     */
    public function __construct(string $author, string $slug)
    {
        $this->published = date('l jS \of F Y h:i:s A');
        $this->author = $author;
        $this->slug = $slug;
    }


    /**
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


    /**
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


    /**
     * @param string $title
     * @param string $text
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

    /**
     * @param object $findings
     * @param string $slug
     * @return string
     */
    abstract function create (object $findings, string $slug): string;

    /**
     * @param object $findings
     * @param string $slug
     * @return mixed
     */
    abstract function reade (string $slug): object;


    /**
     * @param object $inputClass
     * @param string $slug
     * @param string $id
     * @return void
     */
    abstract function update (object $inputClass, string $slug): void;

    /**
     * @param string $slug
     * @return void
     */
    abstract function delete (string $slug): void;

    /**
     * @return array
     */
    abstract function list(): array;
}

abstract class View
{
    public $storage;

    /**
     * @param string $storage
     */
    public function __construct(string $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param int $id
     * @return void
     */
    abstract function displayTextById(int $id): void;

    /**
     * @param string $url
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
    /**
     * @param object $findings
     * @param string $slug
     * @return string
     */
    public function create(object $findings, string $slug): string
    {
        $id=0;
        $numberSlug = 0;
        $slug = $slug2 = $slug . '-' . date('d-m-Y');

        while (file_exists($slug))
        {
            $numberSlug++;
            $slug = $slug2 . '-'.$numberSlug;
        }
        $findings->slug = $slug;
        file_put_contents($slug, serialize($findings));
        return $slug;
    }

    /**
     * @param object $findings
     * @param string $slug
     * @return object
     */
    function reade(string $slug): object
    {
        $inputClass = (object)[];

        if (strpos($slug,'test_text_file') !== false &&
            file_exists($slug) &&
            filesize($slug) > 0)
        {
            $inputClass = unserialize(file_get_contents($slug));
            return $inputClass;
        }
        return $inputClass;
    }

    /**
     * @param object $inputClass
     * @param string $slug
     * @return void
     */
    function update(object $inputClass, string $slug): void
    {
        if (strpos($slug,'test_text_file') !== false &&
            file_exists($slug) )
        {
            file_put_contents($slug, serialize($inputClass));
        }
    }

    /**
     * @param string $slug
     * @return void
     */
    function delete(string $slug): void
    {
        if (strpos($slug,'test_text_file') !== false &&
            file_exists($slug) )
        {
            unlink($slug);
        }
    }

    /**
     * @return array
     */
    function list(): array
    {
        $arrayList = [];
        $indexArrayClass = 0;
        $arrayScanDir = scandir('./');
        $countArrayScanDir = count($arrayScanDir);

        for ($i = 0; $i < $countArrayScanDir; $i++)
        {
            $fileName = './' . $arrayScanDir[$i];


            if (
                strpos($fileName,'test_text_file') &&
                filesize($fileName) > 0
            ) {
                $arrayList[$indexArrayClass] = (array) unserialize(file_get_contents($fileName));
                $indexArrayClass++;
            }
        }

        return $arrayList;

    }
}
$author = 'Автор';
$slug = 'test_text_file';

$objectTelegraphText = new TelegraphText($author, $slug);
$objectTelegraphText->text = 'текст';
$objectTelegraphText->title = 'заголовок';

$objectFileStore = new FileStorage();
$slug=$objectFileStore->create($objectTelegraphText,$slug);
print_r($objectFileStore->reade($slug));

$objectTelegraphText->text = 'Update text';
$objectTelegraphText->title = 'Update title';
$objectFileStore->update($objectTelegraphText,$slug);
print_r($objectFileStore->list());