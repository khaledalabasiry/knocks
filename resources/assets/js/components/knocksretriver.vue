<template>
	<div class="row" v-if = "dev">
	<p style = "font-family : monospace !important">
		<p class = "red-text">is loading :  {{isLoading}}</p>
		<p class = "red-text">progress :  {{loadingPercentage}}</p>
		<p class = "red-text">has errors :  {{hasErrors}}</p>
		<!-- <p class = "red-text">status :  {{status}}</p> -->
		<p class = "red-text">response data :  {{response}}</p>
	</p>
</div>
</template>

<script>
export default {

  name: 'knocksretriver',
  props : {
  	xdata : {
  		type : Object , 
  		default : null , 
  	},
  	method : {
  		type : String , 
  		default : 'post'
  	},
  	url : {
  		type : String , 
  		required : true
  	},
  	free_origin : {
  		type : Boolean , 
  		default : false
  	},
  	timeout : {
  		type : Number , 
  		default : 15000
  	},
  	recursed : {
  		type : Boolean ,
  		default : false 
  	},
  	recursion_time : {
  		type : Number , 
  		default : 10000
  	},
  	recursion_precondition : {
  		type : Boolean , 
  		default : null ,
  	},
  	behind_recursion : {
  		type : Boolean , 
  		default : false ,
  	},
  	dev : {
  		type : Boolean , 
  		default : false 
  	},
  },
  data () {
    return {
    	isLoading : false , 
    	loadingPercentage : null ,
    	response : null , 
    	errors : null , 
    	hasErrors : false ,
    	status : null ,
    	result : null ,
    	windowStatus : true ,
    	recursionStopped : false ,
    }
  },
  computed : {
  	finalUrl(){
  		return this.free_origin ? this.url : LaravelOrgin + this.url;
  	},
 
  },
  mounted(){
  	this.resultCollector();
  	this.retrive();
  	const vm = this;
  	$(document).ready(function(){
  	$(window).focus(function(){
  		vm.windowStatus = true ;
  		console.log('screen on '+moment().format('mm:ss'))
  	    if(vm.behind_recursion || (!vm.behind_recursion && vm.windowStatus) ){
  	       vm.recursionStopped = false;
  	       console.log('resume '+moment().format('mm:ss'))
  	       vm.retrive(); 
  		}
  	});
  	$(window).blur(function(){
  	    vm.windowStatus = false ;
  	    console.log('screen off '+moment().format('mm:ss'))
  	});
  });
  },
  methods : {
  	retrive(){
  		const vm = this;
  		axios({
  			method : vm.method , 
  			url : vm.finalUrl , 
  			data : vm.xdata , 
  			onDownloadProgress : (progressEvent)=>{
  				vm.resultCollector();
  				vm.$emit('progress' , vm.result);
  				vm.isLoading = true;
  				vm.loadingPercentage = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
  			},	
			timeout : vm.timeout ,
  		}).then((res)=>{
  			vm.isLoading = false ;
  			vm.response = res.data;
  		    vm.hasErrors = false ;
  			vm.errors = null;
  			vm.resultCollector();
  			vm.$emit('success' , vm.result);
  			if(vm.recursed && ( vm.recursion_precondition == null || vm.recursion_precondition == true ) ){
  				   setTimeout( ()=>{ 
  				   	if(vm.behind_recursion || (!vm.behind_recursion && vm.windowStatus) ){
  				   		console.log('recursion '+ moment().format('mm:ss') )
  				   		vm.$emit('recursion' , vm.result);
  				    	vm.retrive(); 
  				    }else{
  				    	vm.$emit('stop' , vm.result);
  				    	vm.recursionStopped = true;
  				    	console.log('stop '+moment().format('mm:ss'))
  				    }
  				   } , vm.recursion_time);
  			}
  		}).catch((err)=>{
  			vm.isLoading = false ;
  			vm.hasErrors = true ;
  			vm.errors = err;
  			vm.resultCollector();
  			vm.$emit('catch' , vm.result);
  		});
  	},
  	resultCollector(){
  		this.result = {
  			loading : this.isLoading , 
  			progress : this.loadingPercentage ,
  			response : this.response , 
  			status : this.status , 
  			hasErrors : this.hasErrors , 
  			errors : this.errors , 
  			monitor : {
  				data : this.xdata , 
  				url : this.finalUrl , 
  				timeout : this.timeout , 
  				recursion : this.recursed , 
  				recursion_time : this.recursion_time , 
  				recursion_precondition : this.recursion_precondition , 

  			}
  		}
  		this.$emit('input' , this.result); 
  	}
  }
}
</script>

<style lang="css" scoped>
</style>