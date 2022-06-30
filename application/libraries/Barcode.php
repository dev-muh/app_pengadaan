<?php

class Barcode{
	function print_barcode($code=null,$it_name=null){
			
		//############################# PRINT
			require __DIR__.'/barcode/BarcodeBase.php';
			//require __DIR__.'/Code39.php';
			require __DIR__.'/barcode/Code128.php';

			$bcode = array();

			$bcode['c128']	= array('name' => 'Code128', 'obj' => new emberlabs\Barcode\Code128());
			//$bcode['c39']	= array('name' => 'Code39', 'obj' => new emberlabs\Barcode\Code39());

			function bcode_error($m)
			{
				echo "<div class='error'>{$m}</div>";
			}

			// function bcode_success($bcode_name)
			// {
			// 	echo "<div class='success'>A $bcode_name barcode was successfully created</div>";
			// }

			function bcode_img64($b64str,$it_name=null,$code=null)
			{	
				echo "<center>";
				echo "<p>".strtoupper((substr($it_name, 0, 15)))."</p>";
				echo "<img id='imgbarcode' src='data:image/png;base64,$b64str'/>";
				echo "<p>".$code."</p>";
				echo "</center>";
			}

			

			
				foreach($bcode as $k => $value)
				{
					try
					{
						$bcode[$k]['obj']->setData($code);

						$bcode[$k]['obj']->setDimensions(144, 96);
						$bcode[$k]['obj']->draw();
						$b64 = $bcode[$k]['obj']->base64();

						//bcode_success($bcode[$k]['name']);
						bcode_img64($b64,$it_name,$code);
					}
					catch (Exception $e)
					{
						bcode_error($e->getMessage());
					}
				}
			?>


			<html xmlns="http://www.w3.org/1999/xhtml"> 
				<head> 
					<title>Barcode Print</title> 
					<style type="text/css" media="print"> 
						@page { 
								size: auto; /* auto is the initial value */ 
								margin: 0mm; /* this affects the margin in the printer settings */ 
								} 
						html { 
								background-color: #FFFFFF; 
								margin: 0px; /* this affects the margin on the html before sending to printer */ 
							} 
						body { 
								margin: 0mm 0mm 0mm 0mm; /* margin you want for the content */ 
							}
						
						
					</style> 
				</head> 
				<body></body> 
			</html>

			<script type="text/javascript">
			document.getElementById("imgbarcode").style.marginTop = "-15px"; 
			document.getElementById("imgbarcode").style.marginBottom = "-15px"; 
			window.print()
			</script>

			<?php
		
	}

}

?>