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

class YouTube {

    public $Key;
    public $Error;

    public function __construct($appKey) {
        $this->Key = $appKey;
    }

    // Setup API key from your google app
    public function API($query) {
        if ($query) {
            return ('https://www.googleapis.com/youtube/v3/' . $query);
        } else {
            $this->Error = 'Must be enter your api query';
            $this->error();
        }
    }

    // Get channel info by ID
    public function channel_info($channel_id) {
        $result = array();
        $url = $this->API("channels?part=brandingSettings,snippet&id=" . $this->safe($channel_id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['name'] = $data->items[0]->snippet->title;
                $result['description'] = $data->items[0]->snippet->description;
                $result['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[0]->snippet->publishedAt));
                $result['thumbnails']['default'] = $data->items[0]->snippet->thumbnails->default->url;
                $result['thumbnails']['medium'] = $data->items[0]->snippet->thumbnails->medium->url;
                $result['thumbnails']['high'] = $data->items[0]->snippet->thumbnails->high->url;
                $result['banners'] = $data->items[0]->brandingSettings->image;
                return($result);
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

    // Get channel statistics by ID
    public function channel_statistics($channel_id) {
        $result = array();
        $url = $this->API("channels?part=statistics&id=" . $this->safe($channel_id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['videos_count'] = $data->items[0]->statistics->videoCount;
                $result['comments_count'] = $data->items[0]->statistics->commentCount;
                $result['views_count'] = $data->items[0]->statistics->viewCount;
                $result['subscribers_count'] = $data->items[0]->statistics->subscriberCount;
                return($result);
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

    // Get channel playlists by ID
    public function channel_playlists($channel_id, $count = 10) {
        $result = array();
        $url = $this->API("playlists?part=snippet%2CcontentDetails&channelId=" . $this->safe($channel_id) . "&maxResults=$count&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['playlists_count'] = $data->pageInfo->totalResults;
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result['playlists'][$x]['id'] = $data->items[$x]->id;
                        $result['playlists'][$x]['title'] = $data->items[$x]->snippet->title;
                        $result['playlists'][$x]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->publishedAt));
                        $result['playlists'][$x]['video_count'] = $data->items[$x]->contentDetails->itemCount;
                        $result['playlists'][$x]['url'] = 'https://www.youtube.com/playlist?list=' . $data->items[$x]->id;
                        $result['playlists'][$x]['description'] = $data->items[$x]->snippet->description;
                        $result['playlists'][$x]['thumbnails']['default'] = $data->items[$x]->snippet->thumbnails->default->url;
                        $result['playlists'][$x]['thumbnails']['medium'] = $data->items[$x]->snippet->thumbnails->medium->url;
                        $result['playlists'][$x]['thumbnails']['high'] = $data->items[$x]->snippet->thumbnails->high->url;
                    }
                }
                return($result);
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

    // Get channel videos by ID
    public function channel_videos($channel_id, $count = 10) {
        $result = array();
        $url = $this->API("channels?id=" . $this->safe($channel_id) . "&part=snippet,contentDetails,statistics&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                foreach ($data->items as $id) {
                    $ID = $id->contentDetails->relatedPlaylists->uploads;
                    $result = $this->playlist_videos($ID, $count);
                }
                return($result);
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

    // Get playlists video by ID
    public function playlist_videos($playlist_id, $count = 10) {
        $result = array();
        $url = $this->API("playlistItems?part=snippet%2CcontentDetails&maxResults=$count&playlistId=" . $this->safe($playlist_id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['videos_count'] = $data->pageInfo->totalResults;
                for ($x = 0; $x < $count; $x++) {
                    
					if (!empty($data->items[$x])) {
                        
						$result = json_decode(json_encode($data), True);
				
						/*$video_data = @file_get_contents("http://www.youtube.com/get_video_info?video_id=" . $data->items[$x]->contentDetails->videoId);
                        if ($video_data) {
                            parse_str($video_data);
                        }
                        $result['videos'][$x]['id'] = $data->items[$x]->contentDetails->videoId;
                        $result['videos'][$x]['title'] = $data->items[$x]->snippet->title;
                        $result['videos'][$x]['url'] = 'https://www.youtube.com/watch?v=' . $data->items[$x]->contentDetails->videoId;
                        $result['videos'][$x]['description'] = $data->items[$x]->snippet->description;
                        $result['videos'][$x]['view_count'] = isset($view_count) ? $view_count : '';
                        $result['videos'][$x]['rating_count'] = isset($avg_rating) ? $avg_rating : '';
                        $result['videos'][$x]['author'] = isset($author) ? $author : '';
                        $result['videos'][$x]['keywords'] = isset($keywords) ? $keywords : '';
                        $result['videos'][$x]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->publishedAt));
                        $result['videos'][$x]['thumbnails']['default'] = $data->items[$x]->snippet->thumbnails->default->url;
                        $result['videos'][$x]['thumbnails']['medium'] = $data->items[$x]->snippet->thumbnails->medium->url;
                        $result['videos'][$x]['thumbnails']['high'] = $data->items[$x]->snippet->thumbnails->high->url;
						*/
					}
                }
                return($result);
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

    // Get Search on Channel By ID
    public function channel_search($channel_id, $keyword = '', $count = 10) {
        $result = array();
        $keyword = str_replace(' ', '+', $keyword);
        $url = $this->API("search?channelId=" . $this->safe($channel_id) . "&part=id&order=date&maxResults=$count&q=$keyword&type=video&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
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

    // Get Search on Youtube Videos
    public function public_search($keyword = '', $count = 10) {
        $result = array();
        $keyword = str_replace(' ', '+', $keyword);
        $url = $this->API("search?part=id&order=date&maxResults=$count&q=$keyword&type=video&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
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

    // Get Populars on Channel By ID
    public function channel_popular($channel_id, $count = 10) {
        $result = array();
        $url = $this->API("search?channelId=" . $this->safe($channel_id) . "&part=id&order=viewCount&maxResults=$count&type=video&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
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

    // Get Realted Videos By Video ID
    public function related_video($id, $count = 10) {
        $result = array();
        $url = $this->API("search?part=snippet&relatedToVideoId=" . $this->safe($id) . "-kc&type=video&maxResults=$count&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
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

    // Get Comments By Video ID
    public function video_comments($id, $count = 10) {
        $result = array();
        $url = $this->API("commentThreads?part=snippet%2Creplies&maxResults=$count&videoId=" . $this->safe($id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['comments_count'] = $data->pageInfo->totalResults;
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result['comments'][$x]['id'] = $data->items[$x]->id;
                        $result['comments'][$x]['text'] = $data->items[$x]->snippet->topLevelComment->snippet->textDisplay;
                        $result['comments'][$x]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->publishedAt));
                        $result['comments'][$x]['updated_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->updatedAt));
                        $result['comments'][$x]['author']['name'] = $data->items[$x]->snippet->topLevelComment->snippet->authorDisplayName;
                        $result['comments'][$x]['author']['image'] = $data->items[$x]->snippet->topLevelComment->snippet->authorProfileImageUrl;
                        $result['comments'][$x]['author']['channel_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorChannelUrl;
                        $result['comments'][$x]['author']['googleplus_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorGoogleplusProfileUrl;
                        $result['comments'][$x]['like_count'] = $data->items[$x]->snippet->topLevelComment->snippet->likeCount;
                        if (!empty($data->items[$x]->replies->comments)) {
                            for ($i = 0; $i <= $data->items[$x]->snippet->totalReplyCount; $i++) {
                                $result['comments'][$x]['replies'][$i]['id'] = $data->items[$x]->id;
                                $result['comments'][$x]['replies'][$i]['text'] = $data->items[$x]->snippet->topLevelComment->snippet->textDisplay;
                                $result['comments'][$x]['replies'][$i]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->publishedAt));
                                $result['comments'][$x]['replies'][$i]['updated_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->updatedAt));
                                $result['comments'][$x]['replies'][$i]['author']['name'] = $data->items[$x]->snippet->topLevelComment->snippet->authorDisplayName;
                                $result['comments'][$x]['replies'][$i]['author']['image'] = $data->items[$x]->snippet->topLevelComment->snippet->authorProfileImageUrl;
                                $result['comments'][$x]['replies'][$i]['author']['channel_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorChannelUrl;
                                $result['comments'][$x]['replies'][$i]['author']['googleplus_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorGoogleplusProfileUrl;
                                $result['comments'][$x]['replies'][$i]['like_count'] = $data->items[$x]->snippet->topLevelComment->snippet->likeCount;
                            }
                        }
                    }
                }
                return($result);
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

    // Get video Data by ID
    public function video_info($id) {
        $result = array();
        $url = $this->API("videos?part=snippet&status&player&id=" . $this->safe($id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
		
            $data = json_decode($json);
				/*
            parse_str($json);
            $result['id'] = isset($video_id) ? $video_id : '';
            $result['title'] = isset($title) ? $title : '';
            $result['description'] = isset($description) ? $description : '';
            $result['url'] = isset($video_id) ? 'https://www.youtube.com/watch?v=' . $video_id : '';
            $result['published_date'] = date('d/m/Y H:i:s', isset($timestamp) ? $timestamp : 0);
            $result['view_count'] = isset($view_count) ? $view_count : '';
            $result['rating_count'] = isset($avg_rating) ? $avg_rating : '';
            $result['author'] = isset($author) ? $author : '';
            $result['keywords'] = isset($keywords) ? $keywords : '';
            $result['thumbnails']['default'] = isset($thumbnail_url) ? $thumbnail_url : '';
            $result['thumbnails']['medium'] = isset($iurlmq) ? $iurlmq : '';
            $result['thumbnails']['high'] = isset($iurlhq) ? $iurlhq : '';
            $result['thumbnails']['hd'] = isset($iurlsd) ? $iurlsd : '';
            if (isset($url_encoded_fmt_stream_map)) {
                $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
                if (count($my_formats_array) != 0) {
                    $avail_formats[] = '';
                    $i = 0;
                    $ipbits = $ip = $itag = $sig = $quality = '';
                    $expire = time();
                    foreach ($my_formats_array as $format) {
                        parse_str($format);
                        $avail_formats[$i]['itag'] = $itag;
                        $avail_formats[$i]['quality'] = $quality;
                        $type = explode(';', $type);
                        $avail_formats[$i]['type'] = $type[0];
                        $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
                        parse_str(urldecode($url));
                        $avail_formats[$i]['expire'] = date("G:i:s T", $expire);
                        $avail_formats[$i]['ipbits'] = $ipbits;
                        $avail_formats[$i]['ip'] = $ip;
                        $i++;
                    }
                    for ($i = 0; $i < count($avail_formats); $i++) {
                        $result['formats'][$i]['itag'] = $avail_formats[$i]['itag'];
                        $result['formats'][$i]['quality'] = $avail_formats[$i]['quality'];
                        $result['formats'][$i]['type'] = $avail_formats[$i]['type'];
                        $result['formats'][$i]['url'] = $avail_formats[$i]['url'];
                    }
                }
				
            }
			*/
			$result = json_decode(json_encode($data), True);
            return($result);
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Download video by ID
    public function download_video($id) {
        $result = array();
        $json = @file_get_contents("http://www.youtube.com/get_video_info?video_id=" . $this->safe($id) . "");
        if ($json) {
            parse_str($json);
            if (isset($url_encoded_fmt_stream_map)) {
                $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
                if (count($my_formats_array) != 0) {
                    $avail_formats[] = '';
                    $i = 0;
                    $ipbits = $ip = $itag = $sig = $quality = '';
                    $expire = time();
                    foreach ($my_formats_array as $format) {
                        parse_str($format);
                        $avail_formats[$i]['itag'] = $itag;
                        $avail_formats[$i]['quality'] = $quality;
                        $type = explode(';', $type);
                        $avail_formats[$i]['type'] = $type[0];
                        $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
                        parse_str(urldecode($url));
                        $avail_formats[$i]['expire'] = date("G:i:s T", $expire);
                        $avail_formats[$i]['ipbits'] = $ipbits;
                        $avail_formats[$i]['ip'] = $ip;
                        $i++;
                    }
                    for ($i = 0; $i < count($avail_formats); $i++) {
                        $result[$i]['type'] = str_replace('video/', '', $avail_formats[$i]['type']) . ' - ' . $avail_formats[$i]['quality'];
                        $result[$i]['url'] = $avail_formats[$i]['url'];
                    }
                }
            }
            $data = explode('&', $json);
            return($result);
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get channel ID by user name
    public function channelID_byUsername($username) {
        $result = '';
        $url = $this->API("channels?part=snippet&forUsername=" . $this->safe($username) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result = $data->items[0]->id;
                return($result);
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

    // Get playlist ID by URL
    public function playlistID_byUrl($url) {
        $query = parse_url($url);
        parse_str($query['query'], $id);
        return (isset($id['list'])) ? $id['list'] : NULL;
    }

    // Get video ID by URL
    public function videoID_byUrl($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $id);
        return (isset($id['v'])) ? $id['v'] : NULL;
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

