<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

use App\Models\Download;

class processDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
	
	/**
     * Get file size
     *
     * @return void
     */
	protected function getFileSize($url) 
	{
		
		// Assume failure.
	  	$result = -1;

  		$curl = curl_init($url);

	  	// Issue a HEAD request and follow any redirects.
	  	curl_setopt($curl, CURLOPT_NOBODY, true);
	  	curl_setopt($curl, CURLOPT_HEADER, true);
	  	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	  	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	  	//curl_setopt($curl, CURLOPT_USERAGENT, get_user_agent_string());

  		$data = curl_exec($curl);
  		curl_close($curl);

  		if ($data) {
    		$content_length = "unknown";
    		$status = "unknown";

	    	if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches)) {
	      		$status = (int)$matches[1];
	    	}
	
	    	if (preg_match("/Content-Length: (\d+)/", $data, $matches)) {
	      		$content_length = (int)$matches[1];
	    	}
	
	    	if( $status == 200 || ($status > 300 && $status <= 308) ) {
	      		$result = $content_length;
	    	}
  		}

  		return $result;
	}
	
	/**
     * Get file data
     *
     * @return void
     */
	protected function getFileData($url, $temp, $to) 
	{
		set_time_limit(0);
		$fp = fopen($temp, 'w+');
		$ch = curl_init(str_replace(" ", "%20", $url));
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FILE, $fp); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch); 
		curl_close($ch);
		fclose($fp);
		
		if (!file_exists($temp)) {
			return false;
		}
		
		return rename($temp, $to);
	}
	
	/**
     * Create file directories
     *
     * @return void
     */
	protected function createDirectories($directories, $path) 
	{
		foreach ($directories as $directory) {
			$path .= $directory;
			if (! is_dir($path)) {
				mkdir($path);
			}
			$path .= '/';
		}
	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	// Processes only one download at a time
        if (Download::where('upload_status', 1)->count() != 0) {
        	return;
        }
		
		// Get older download to be processed
		$download = Download::where('upload_status', 0)->orderBy('created_at', 'ASC')->first();
		if(! $download) {
			return;
		}
		
		// Check available space
		$root_path = substr(base_path(), 0, -4);
		$completed_path = $root_path.'completed/';
		$download_path = $root_path.'download/';
		if($this->getFileSize($download->url) > disk_free_space($completed_path)) {
			return;
		}
		
		// Lock download status
		$download->upload_status = 1;
		$download->save();
		
		// Create directories
		$directories = explode('/', $download->destination);
		array_pop($directories);
		$this->createDirectories($directories, $completed_path);
		$this->createDirectories($directories, $download_path);
		
		// Save files
		$extension = File::extension($download->url);
		$save = $this->getFileData($download->url, $download_path.$download->destination.'.'.$extension, $completed_path.$download->destination.'.'.$extension);
		
		// Unlock download status
		$download->upload_status = $save ? 2 : -1;
		$download->save();
    }
}
