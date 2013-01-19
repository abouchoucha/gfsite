<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>

<div class="SearchResults">
        <?php if(count($this->hits)==0) {?>
            <div id="noSearchResults">
            	 <h2>There were <strong>no matches</strong> to your search for: <strong style="color:red;">"<?php echo $this->searchTerm ?>"</strong></h2>
            	 <p>Suggestions:</p>
            	 <ul>
            			<li>Make sure all words are spelled correctly.</li>
            			<li>Try different keywords.</li>
            			<li>Try more generic keywords.</li>
            	 </ul>
            	 <div>
                	 <ul>
                    	 <?php foreach($this->topSearches as $topSearch ) {?>
                                <li>- <a href="<?php echo $urlGen->getSearchUrl($topSearch["searchTerm"],True) ?>" title="search for <?php echo $topSearch["searchTerm"] ?>"><?php echo $topSearch["searchTerm"] ?></a></li>
                    	 <?php }?>
                	 </ul>
            	 </div>
            </div>
        
        <?php } else { ?>
            <?php foreach ($this->paginator as $hit) { ?>
               <?php if($hit instanceof Player){?>
                    <div>
                        <?php $hit->player_firstname ?>
                    </div>
                <?php } elseif ($hit->category == "club") {?>
                      <div>
                        <a href="<?php echo $hit->url ?>" title="<?php echo $hit->articletitle ?>" style="float: left;">
                            <img height="100px" width="100px" style="border-width: 0px;" src="http://cache.images.soccerway.com/new/teams/<?php echo substr($hit->url, strpos($hit->url, "_",0) + 1, strlen($hit->url) - strpos($hit->url, "_",0) -2)?>.gif" />
                        </a>
                        <ul>
                            <li class="Username"><a href="<?php echo $hit->url ?>"><?php echo ucwords(urldecode(str_ireplace("-", " ", substr(substr(strrchr((substr($hit->url, 0, strlen($hit->url) -2)), "/"), 1), 0, strpos(substr(strrchr((substr($hit->url, 0, strlen($hit->url) -2)), "/"), 1), "_"))))) ?></a></li>
                            <li class="Description"><?php echo $this->query->highlightMatches(substr($hit->contents, 64)) ?></li>
                            <li><a href="<?php echo $hit->url ?>"
                                title="<?php echo $hit->articletitle ?>"><?php echo urldecode("http://".$_SERVER["SERVER_NAME"].$hit->url) ?></a></li>
                        </ul>
                    </div>
                <?php } elseif ($hit->category == "leagues") {?>    
                      <div>
                         <h5 class="TeamPage">Team Page</h5>
                         <ul>
                            <li class="Username">Username</li>
                            <li>Gender, Age</li>
                            <li class="Description"><?php echo $this->query->highlightMatches($hit->contents) ?></li>
                            <li><a href="<?php echo $hit->url ?>" title="<?php echo $hit->articletitle ?>"><?php echo $hit->url ?></a></li>                                       
                         </ul>
                      </div>
                <?php } elseif ($hit->category == "scores") {?>  
                      <div>
                         <h5 class="TeamPage">Team Page</h5>
                         <ul>
                            <li class="Username">Username</li>
                            <li>Gender, Age</li>
                            <li class="Description"><?php echo $this->query->highlightMatches($hit->contents) ?></li>
                            <li><a href="<?php echo $hit->url ?>" title="<?php echo $hit->articletitle ?>"><?php echo $hit->url ?></a></li>                                      
                         </ul>
                      </div>
                
            <?php } elseif ($hit->category == "news") {?>
                    <div>
                        <h5 class="TeamPage">News Page</h5>
                        <ul>
                            <li class="Username"><?php $list= split('/', $hit->url); echo ucwords(urldecode(str_ireplace('-', ' ', $list[3]))) ?></li>	
                            <li class="Description"><?php echo $this->query->highlightMatches(substr($hit->contents, 50)) ?></li>
                            <li><a href="<?php echo $hit->url ?>"
                                title="<?php echo $hit->articletitle ?>"><?php echo urldecode("http://".$_SERVER["SERVER_NAME"].$hit->url) ?></a></li>
                        </ul>
                    </div>

                <?php } elseif ($hit->category == "profiles") {?>
                                                                  <div>
                <h5 class="TeamPage">Profiles Page</h5>
                    <ul>
                        <li class="Username"><?php $list= split('/', $hit->url); echo ucwords(urldecode(str_ireplace('-', ' ', $list[3]))) ?></li>
                        <li class="Description"><?php echo $this->query->highlightMatches(substr($hit->contents, 50)) ?></li>
                        <li><a href="<?php echo $hit->url ?>"
                            title="<?php echo $hit->articletitle ?>"><?php echo urldecode("http://".$_SERVER["SERVER_NAME"].$hit->url) ?></a></li>
                    </ul>
                </div>

                <?php } ?>
            <?php } ?>
        <?php } ?>

                       
                                    
                                    
</div>