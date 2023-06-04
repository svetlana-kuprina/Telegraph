<?php
interface  LoggerInterface
{
    /**
     * @param string $textError
     * @return void
     */
    public function logMessage(string $textError): void;

    /**
     * @param int $numbMessages
     * @return array
     */
    public function lastMessages(int $numbMessages): array;
}
interface EventListenerInterface
{
    /**
     * @param string $nameMeted
     * @param string $callBackFunction
     * @return void
     */
    public function attachEvent(string $nameMeted,string $callBackFunction): void;

    /**
     * @param string $nameMeted
     * @return void
     */
    public function detouchEvent(string $nameMeted): void;

}


abstract class Storage implements LoggerInterface,EventListenerInterface
{
    /**
     * @param string $textError
     * @return void
     */
    abstract function logMessage(string $textError):void;

    /**
     * @param int $numbMessages
     * @return mixed
     */
    abstract function lastMessages(int $numbMessages):array;

    /**
     * @param string $nameMeted
     * @param string $callBackFunction
     * @return void
     */
    abstract function attachEvent(string $nameMeted,string $callBackFunction): void;

    /**
     * @param string $nameMeted
     * @return void
     */
    abstract function detouchEvent(string $nameMeted): void;
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

abstract class User implements EventListenerInterface
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

    /**
     * @param string $nameMeted
     * @param string $callBackFunction
     * @return void
     */
    abstract function attachEvent(string $nameMeted, string $callBackFunction):void;

    /**
     * @param string $nameMeted
     * @return void
     */
    abstract function detouchEvent(string $nameMeted): void;
}


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

    /**
     * @param string $textError
     * @return void
     */
    public function logMessage(string $textError): void
    {
        $numberSlug = 0;
        $slugStart = './log_text_file/log_text_file';
        $slug = $slugNumber = $slugStart . '_' . date('d-m-Y');

        while (file_exists($slug))
        {
            $numberSlug++;
            $slug = $slugNumber . '_' . "$numberSlug";
        }

        file_put_contents($slug, $textError);
    }

    /**
     * @param int $numbMessages
     * @return array
     */
    public function lastMessages(int $numbMessages): array
    {
        $arrayMessages = [];

        $indexArrayMessages = 0;
        $arrayScanDir = scandir('./log_text_file/');
        $countArrayScanDir = count($arrayScanDir);

        if (
            $countArrayScanDir < $$numbMessages
        )
        {
            echo 'Столько логов не будет. Всего логов - ' . $countArrayScanDir .
                ' Уменьшите запрашиваемое количество' . PHP_EOL;
            return $arrayMessages;
        }

        $iStart = $countArrayScanDir - $$numbMessages;

        for ($i = $iStart; $i < $countArrayScanDir; $i++)
        {
            $fileName = './log_text_file/' . $arrayScanDir[$i];

            if (
                strpos($fileName, 'log_text_file', 15) === 16 &&
                filesize($fileName) > 0
            )
            {
                $arrayMessages[$indexArrayMessages] = file_get_contents($fileName);
                $indexArrayMessages++;
            }

        }

        return $arrayMessages;
    }

    /**
     * @param string $nameMeted
     * @param string $callBackFunction
     * @return void
     */
    public function attachEvent(string $nameMeted, string $callBackFunction): void
    {
        global $arrayEvent;

        $arrayEvent = ['nameMeted' => $nameMeted, 'callBackFunction' => $callBackFunction];
    }

    /**
     * @param string $nameMeted
     * @return void
     */
    public function detouchEvent(string $nameMeted): void
    {
        global $arrayEvent;

        unset($arrayEvent[array_search($nameMeted,array_column($arrayEvent,'nameMeted'))]);
        $arrayEvent = array_values($arrayEvent);
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