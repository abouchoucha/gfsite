<div id="ScorecardContainer">
 
           <div class="Scores">
                                          
               <ul class="MatchesDate">
                                        <li>  
                                        Head 2 Head </li>
                                     </ul>
                                      
                                        <?php foreach ($this->head2headList as $head) { ?>
                                         <ul id="h2h">
                                         	<li class="TeamName"><?php echo date ('F j , Y' , strtotime($head['mdate']));?></li>
                                         	<li class="TeamName"><?php echo $head["competition_name"];?></li>
                                            <li class="TeamName"><?php echo $head["teama"];?></li>
                                            <li class="Score"><a href="/goalface/scoreBoard/showMatchDetail/matchid/<?php echo $this->escape($head["matchid"]);?>"><?php echo $this->escape($head["fs_team_a"]);?></a>
                                                    -
                                          <a href="/goalface/scoreBoard/showMatchDetail/matchid/<?php echo $this->escape($head["matchid"]);?>"><?php echo $this->escape($head["fs_team_b"]);?></a></li>
                                            <li class="TeamName Winner"><?php echo $head["teamb"];?></li>
                                            
                                        </ul>
                                        <?php } ?>
            </div>
 </div>