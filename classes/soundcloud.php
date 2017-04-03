<?php

error_reporting(E_ALL);

/*
 * 
 * YouTube API Channels PHP Class
 * 
 * Grab your youtube channel and get all videos and playlist , search on channel and get popular videos
 * 
 * PHP Version 5.x
 *
 * Author Tatwerat-Team 
 * 
 * Author-Account http://codecanyon.net/user/tatwerat-team 
 * 
 * Version 1.2.1
 *
 */

 class SoundCloud {

    public $db;
    public $Key;
    public $Error;

    public function __construct($appKey) {
        $this->Key = $appKey;
    }

    // Setup API key from your google app
    public function API($query) {
        if ($query) {
            return ('http://api.soundcloud.com/' . $query);
        } else {
            $this->Error = 'Must be enter your api query';
            $this->error();
        }
    }

    // Resolve URLs
    public function Resolve($url) {
        if ($url) {
            $json = 'http://api.soundcloud.com/resolve?url='. $url .'&client_id=' . $this->Key;
			$data = json_decode(@file_get_contents($json));
				
				$array = json_decode(json_encode($data), True);
				
				
				if (isset($array['kind']) && $array['kind'] == 'track') {
					
					$output = $array;
				
				} elseif (isset($array['kind']) && $array['kind'] == 'user') {
					
					$output = $this->user_tracks($array['id']);
				
				} elseif (isset($array['kind']) && $array['kind'] == 'playlist') {
					
					$output = $this->sets_tracks($array['id']);
				
				} else {
				
					$output = 'Error';
				
				}
				
				return $output;
				
        } else {
            $this->Error = 'Must be enter your api query';
            $this->error();
        }
    }

    // Get User tracks by ID
    public function user_tracks($channel_id) {
        $result = array();
        $url = $this->API("users/" . $this->safe($channel_id) . "/tracks?client_id=" . $this->Key);
		$json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data) {
				$array = json_decode(json_encode($data), True);
                return($array);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get Playlist tracks by ID
    public function sets_tracks($channel_id) {
        $result = array();
        $url = $this->API("playlists/" . $this->safe($channel_id) . "?client_id=" . $this->Key);
		$json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data) {
				$array = json_decode(json_encode($data), True);
                return($array);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }
	
    
    // Get Tracks by Search
    public function file_get_contents_curl($url) {
		
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		return str_replace($entities, $replacements, urlencode($url));

	} 
	
	
	
    public function search_tracks($query) {
        $result = array();
        $url = $this->API("tracks?q=" . $query . "&limit=50&client_id=" . $this->Key);
	
		$encode = $this->file_get_contents_curl($url);
		
		$json = @file_get_contents($encode);
		  
        if ($json) {
            $data = json_decode($json);
            if ($data) {
				$array = json_decode(json_encode($data), True);
				$view = $this->search_result_view($array);
                return($view);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    
    
    // Get Tracks by Search
    public function search_result_view($array) {
		
		$media_class = new media;
		$media_class->db = $this->db;
		$media_class->mediaCatType = '1';
		$cat_query = $media_class->getMediaCats();
		$cats = list_options_active(0, $cat_query);
		
        if (is_array($array)) {
			
			$return = '<ul class="list-group alt">';
			foreach ($array as $row) {
				$return .= '
			
			<li class="list-group-item clear clearfix">
					<div class="media col-md-6" style="margin-bottom:5px;">
                          <span class="pull-left thumb-sm"><img src="'. $row['artwork_url'].'" width="80" /></span>
                          <div class="media-body"><div><a href="'.$row['permalink_url'].'">'. $row['title'].'</a></div>
						  <small class="text-muted"><a href="'.$row['user']['permalink_url'].'">'.$row['user']['username'].'</a></small></div>
                        </div>
					</div>
						
						
				<form class="form-horizontal  col-md-6" id="grab_soundcloud">

					<input type="hidden" name="form_type" value="sc_track">
					<input type="hidden" name="url" value="'.$row['permalink_url'].'">
					<input type="hidden" name="sc_public" value="on">
					
                    <div class="form-group">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-8">
							<select data-required="true" name="sc_cat" required="required" title="Fill this field" class="form-control parsley-validated">
								<option value="">Please choose category</option>
                                '.$cats.'
                          </select>
                      </div>
                      <div class="col-sm-2">
							<div class="ui buttons form-group m-l" style="display: inline-block;">
								<button class="ui positive button" style="padding: 11px;font-size: 12px;" type="submit">Grab now</button>
							</div>
                      </div>
                    </div>
					
                  </form>
						
						</li>
				 ';
			}
			$return .= '</ul>';
			return $return;
			
        } else {
            
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    
    
    // Dumb array
    public function dumb_array($array) {
        echo '<pre style="overflow:auto; width:100%;">';
        print_r($array);
        echo '</pre>';
    }

    // safe values
    private function safe($value) {
        return trim(htmlspecialchars($value));
    }

    // Show errors when function can't get data
    public function error() {
        if ($this->Error)
            echo('<div class="yt-error" style="padding:15px;color:red;margin:10px;border:1px solid red;border-radius:2px;">' . $this->Error . '</div>');
    }

}

