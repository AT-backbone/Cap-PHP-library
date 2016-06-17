<?php

	/*
	cap_array = {
		aid = {
			name
			ty = {
				level
				type
				text
				text_0
				inst_0
				text_1
				inst_1
				from
				to
				ident
			}
		}
	}
	*/
	$cap_array = json_decode($_POST['cap_array']);

	print_r($cap_array);
	foreach($cap_array as $aid => $warr)
	{
		print '<p>';
		print $warr->name;
		print ' ';
		foreach($warr as $key => $warrning)
		{
			if(isset($warrning->level))
			{
				print '<br>';
				print $warrning->level;
				print ' ';
				print $warrning->type;
			}
		}
	}

?>