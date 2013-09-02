<?php
namespace vollossy\FrontController;
use vollossy\FrontController\Exceptions\UnknownRequestPropertyException;

/**
 * Class Request
 * Представляет собой запрос к контроллеру, предоставляет возможность получать параметры из запроса
 * @package vollossy\FrontController
 */
class Request
{
    /**
     * @var array|null Параметры, переданные в запросе
     */
    protected $properties = array();

    /**
     * @param null $testData Тестовые данные для проведения модульных тестов
     */
    public function __construct($testData = null)
    {
        if (!isset($testData)) {
            if (isset($_SERVER['REQUEST_METHOD'])) {
                $this->properties = $_REQUEST;
            }
        } else {
            $this->properties = $testData;
        }
    }

    /**
     * Получает значение параметра запроса по указанному имени и выбрасывает исключение, если параметра с заданным именем
     * не найдено
     * @param $key Имя параметра
     * @return mixed
     * @throws UnknownRequestPropertyException
     */
    public function getProperty($key)
    {
        if(isset($this->properties[$key])){
            return $this->properties[$key];
        } else
            throw new UnknownRequestPropertyException;
    }

    /**
     * Возвращает словарь параметров, переданных действию
     * @return array
     */
    public function getActionParams()
    {
        $result = array();
        foreach ($this->properties as $propertyName => $propertyValue) {
            if($propertyName != "action")
                $result[$propertyName] = $propertyValue;
        }

        return $result;
    }

}
