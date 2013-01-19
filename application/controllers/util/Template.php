<?php
require_once 'Zend/Registry.php';
class Template 
{
	
	/* 	Variables we will be using in this class
	 	template_dir - Simply for organisational reasons, the directory of out template files. 
		file_ext - For organisational reasons, the templae file extensions
		buffer - The contents of the given template file that we work with.
	*/
	
	private $template_dir;
	private $file_ext 		= '.mail';
	private $buffer;
	
	public function __construct (){
		$config = Zend_Registry::get('config');
		$this->template_dir = $config->path->templates->email;
	}
	
	public function buff_template ($file) 
	{
		if( file_exists( $this -> template_dir . $file . $this -> file_ext ) )
		{
			$this -> buffer = file_get_contents( $this -> template_dir . $file . $this -> file_ext );
			
			// For < PHP 4.3.0
			// $this -> buffer = join('', file( $this -> template_dir . $file . $this -> file_ext ));
			
			return $this -> buffer;
		}
		else
		{
			echo $this -> template_dir . $file . $this -> file_ext . ' does not exist';
		}
	}
	
	public function parse_variables($input, $array)
	{	
			$search = preg_match_all('/{.*?}/', $input, $matches);
						
			for($i = 0; $i < $search; $i++)
			{
				$matches[0][$i] = str_replace(array('{', '}'), null, $matches[0][$i]);
			
			}
			
			foreach($matches[0] as $value)
			{
				$input = str_replace('{' . $value . '}', $array[$value], $input);
			}
			 	
		
			return $input;
	}

}

?>