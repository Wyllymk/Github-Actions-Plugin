<?php
/**
 * @package GithubActions
*/

namespace GAP;

if( ! class_exists('Github_Actions_Init')){

    final class Github_Actions_Init{
        /**
         * Store all classes in an array
         * @return array Full list of classes
         */
        public static function getServices(){
            return [
                Pages\Github_Actions_Admin::class,
                Base\Github_Actions_Enqueue::class,
                Base\Github_Actions_Settings::class,
                Base\Github_Actions_Update::class,
                Theme\Github_Actions_Trigger_Workflow::class,
                Theme\Github_Actions_Webhook::class,
            ];
        }
        /**
         * Loop through the classes, initialize them, and call the register() method if it exists
         * @return  
         */        
        public static function registerServices(){
            foreach(self::getServices() as $class){
                $service = self::instantiate($class);
                if(method_exists($service, 'register')){
                    $service->register();
                }
            }
        }
        /**
         * Initialize the class 
         * @param class $class class from the services array
         * @return class instance of the class 
         */
        private static function instantiate($class){
            $service = new $class();
            return $service;
        }

    }
    
}