<?php
namespace vollossy\FrontController;
use vollossy\FrontController\Exceptions\UnknownRequestPropertyException;

/**
 * Class Controller
 * Класс-контроллер выполняющий всю работу по поиску и выполнению действий внутри приложения
 * @package vollossy\FrontController
 */
class Controller
{
    /**
     * @var string Действие, которое должно производиться по умолчанию
     */
    protected $defaultAction = 'index';

    /**
     * @var string Название функции, выполняющей текущее действие. Нужно для тестирования
     */
    protected $currentActionFunction = '';

    public static function run()
    {
        $className =    static::getClassName();

        $instance = new $className;
        $instance->executeAction(new Request);
    }

    protected static function getClassName(){
        $className = __CLASS__;
        return $className;
    }

    /**
     * Ищет в текущем классе классе функцию, имя которой соответствует шаблону action<Name>, где name - значение параметра
     * запроса action. Если этот параметр не указан, то вместо Name подставляется значение свойства Controller::$defaultAction
     * @param Request $request Экземпляр текущего запроса
     */
    private function executeAction(Request $request)
    {
        try{
            $actionName = $request->getProperty('action');
        } catch(UnknownRequestPropertyException $exc){
            $actionName = $this->defaultAction;
        }
        $this->currentActionFunction = 'action'.strtoupper($actionName[0]).substr($actionName, 1);
        $reflectionInstance = new \ReflectionClass($this);
        $reflectionMethod = $reflectionInstance->getMethod($this->currentActionFunction);

        $actionParams = $request->getActionParams();
        $paramsToPass = array();
        /** @var $param \ReflectionParameter */
        foreach ($reflectionMethod->getParameters() as $param) {
            if(isset($actionParams[$param->getName()])){
                $paramsToPass[] = $actionParams[$param->getName()];
            } else {
                $paramsToPass[] = $param->getDefaultValue();
            }
        }

        $reflectionMethod->invokeArgs($this, $paramsToPass);
    }

    /**
     * Отображает представление с указанным именем плюс расширени .php и данными. Все представления хранятся в папке views,
     * а данные для представления доступны через массив $data;
     * @param $viewName Имя представления.
     * @param array $data Данные для представления
     */
    protected function render($viewName, /** @noinspection PhpUnusedParameterInspection */
                              $data = array()){
        /** @noinspection PhpIncludeInspection */
        require_once "views/{$viewName}.php";
    }

}
