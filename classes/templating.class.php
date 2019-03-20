<?php
class templating
{
	var $assignedValues = array();
	var $tpl;

	function __construct($_path="")
	{
		if(!empty($_path))
		{
			if(file_exists($_path))
			{
				$this->tpl = file_get_contents($_path);
			}
			else{
					echo "<b>Template error:</b> File inclusion error!";
				}
		}
	}


	function assign($_string_to_search_for, $_string_to_replace_with)
	{
		if(!empty($_string_to_search_for))
		{

			$this->assignedValues[strtoupper($_string_to_search_for)] = $_string_to_replace_with;

		}
	}

	function show()
	{
		if(count($this->assignedValues > 0 ))
		{

			foreach($this->assignedValues as $key => $value)
			{
				if(is_array($value)):
					foreach ($value as $key => $val) {
						$this->tpl = str_replace('{{{'.$key.'}}}',$val,$this->tpl );
					}
				else:
					$this->tpl = str_replace('{{{'.$key.'}}}',$value,$this->tpl );
				endif;
			}
		}

		 return $this->tpl;

		}

	}

?>
