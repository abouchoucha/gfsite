<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>

 <?php if ($this->playerposition != 'Goalkeeper'){ ?>
 <table class="PlayerData">


            <tr>
                <th class="NoLeftBorder">Season</th>
                <th class="LeftAlign">Team</th>
                <th class="LeftAlign">League</th>
                <th>Gp</th>
                <th>Sb</th>
                <th>Min</th>
                <th>Gl</th>
                <!--<th>Pn</th>
                <th>As</th>
                --><th>Yc</th>                                               
                <th class="NoRightBorder">Rc</th>
            </tr>  

                <?php $i = 1; ?>
            				<?php foreach ($this->playerSeasonFull as $details)  {
            				    
                         if($i % 2 == 1){$style = "";}else{$style = "Even";} 
                          					  
            				?>
        
                    <tr class="<?php echo $style; ?>">
                        <td class="LeftAlign"><?php echo $details["season_name"]; ?></td>
                        <td class="LeftAlign"><a href="<?php echo $urlGen->getClubMasterProfileUrl($details["team_id"], $details["team_seoname"], true)  ?>"><?php echo $details["team_name"]; ?></a></td>
                        <td><?php echo $details["competition_name"]; ?></td>
                        <td><?php echo $details["gp"]; ?></td>
                        <td class="RightAlign"><?php echo $details["sb"]; ?></td>
                        <td><?php echo $details["minp"]; ?></td>
                        <td><?php echo $details["gl"]; ?></td>
                         <!--<td><?php //echo $details["pn"]; ?></td>
                       <td><?php //echo $details["ast"]; ?></td>
                        --><td><?php echo $details["yc"]; ?></td>                        
                        <td class="NoRightBorder"><?php echo $details["rc"]; ?></td>
                    </tr>
                    
        		        <?php $i++; } ?>	     
   </table> 
   
                       <div class="StatLegend">                         
                        <ul class="Abbreviation">
                            <li>Gp</li>
                            <li>Sb</li>
                            
                       
                        </ul>
                        <ul class="Full">
                            <li>Games Played</li>
                            <li>Games as Sub</li> 
                        </ul>               
                         <ul class="Abbreviation">
            								<li>Min</li>
                            <li>Gl</li>
                           
                           
                        </ul>
                        <ul class="Full">    
                           	<li>Minutes Played</li>
                            <li>Goals</li>       
                        </ul> 
                         <ul class="Abbreviation">
                             <li>Yc</li>
                            <li>Rc</li>
                        </ul>
               					<ul class="Full">
                              <li>Yellow Cards</li>
                            <li>Red Cards</li>
       
                        </ul>
                        <ul class="Abbreviation">
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
     
                        </ul>
                        <ul class="Full Last">
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
              
                        </ul>  
                    </div> 
   
    <?php } else { ?>	
    <table class="PlayerData">
        <tr>
            <th class="NoLeftBorder">Season</th>
            <th class="LeftAlign">Team</th>
            <th class="LeftAlign">League</th>
            <th>Gp</th>
            <th>Min</th>
            <th>Sb</th>
            <th>Ga</th>
            <th>Gavg</th>
            <th>Cs</th>
            <th>YC</th>
            <th>RC</th>
    		</tr>
    		 <?php $i = 1; ?>
    				<?php foreach ($this->playerSeasonFull as $details)  {
    				    
                 if($i % 2 == 1){$style = "";}else{$style = "Even";} 
                  					  
    				?>

            <tr class="<?php echo $style; ?>">
                <td class="LeftAlign"><?php echo $details["season_name"]; ?></td>
                <td class="LeftAlign"><a href="<?php echo $urlGen->getClubMasterProfileUrl($details["team_id"], $details["team_seoname"], true)  ?>"><?php echo $details["team_name"]; ?></a></td>
                <td><?php echo $details["competition_name"]; ?></td>
              	<td><?php echo $details["gp"]; ?></td>
              	<td><?php echo $details["minp"]; ?></td>
                <td class="RightAlign"><?php echo $details["sb"]; ?></td>
                <td><?php echo $details["ga"]; ?></td>
                <td><?php echo $details["gavg"]; ?></td>
                <td><?php echo $details["cs"]; ?></td>
                <td><?php echo $details["yc"]; ?></td>
                <td><?php echo $details["rc"]; ?></td>
            </tr>
            
		        <?php $i++; } ?>	     
    </table>
    
    <div class="StatLegend">                         
        <ul class="Abbreviation">
            <li>Gp</li>
            <li>Sb</li>           
        </ul>
        <ul class="Full">
            <li>Games Played</li>
            <li>Games as Sub</li>
        </ul>  
        <ul class="Abbreviation">
       			<li>Min</li>
            <li>Ga</li>           
        </ul>
         <ul class="Full">
            <li>Minutes Played</li>
            <li>Goals Allowed</li>
        </ul>  
        <ul class="Abbreviation">
          	<li>Gavg</li>
            <li>Cs</li>      
        </ul>
         <ul class="Full">
            <li>Goals Allowed Avg</li>
            <li>Clean Sheets</li>
        </ul>  
                     
         <ul class="Abbreviation">   
            <li>YC</li>
            <li>RC</li>
        </ul>
        <ul class="Full Last">
            <li>Yellow Cards</li>
            <li>Red Cards</li>
        </ul> 
	</div> 
    <?php } ?>	
    
    
   