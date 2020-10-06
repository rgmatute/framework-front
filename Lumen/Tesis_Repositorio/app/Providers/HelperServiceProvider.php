<?php 
	namespace App\Providers;

	use Illuminate\Support\ServiceProvider;

	class HelperServiceProvider extends ServiceProvider {
		
		public function boot(){
		
		}

		public function register(){
			foreach (glob(base_path('app').'/Helpers/*.php') as $filename){
			   require_once($filename);
			}
		}
	}
?>