<template>
	<div v-if = "userObject != null">
		<el-popover
        ref="addtomypeople"
        placement="right"
        width="200"
        trigger="hover">
        <static_message msg= "Add ** to my people" replaceable :replacements = "[{target : '**' , body : userObject.name}]"></static_message>
        </el-popover>
        <el-popover
        ref="circlestoaccept"
        placement="bottom"
        @show = "updateBalloonsTimer()"
        @hide="runBalloonsTimer()"
        trigger="click">
        <static_message msg= "Search where you want to add ** or create a new circle." classes = "knocks_text_dark knocks_text_ms"
        replaceable :replacements = "[{target : '**' , body : userObject.name}]">
        </static_message>
        <ul class="uk-list uk-list-striped">
          <knocksquickaddcircle v-model="userCirlces"></knocksquickaddcircle>
          <li v-for = "(circle , index) in userCirlces">            
             <el-checkbox v-model="checkedCircles[circle]" class = "bounceInLeft" ></el-checkbox>
             <knockscirclechip class = "animated bounceInRight" :circle="circle" :popover = "false"></knockscirclechip>
          </li>
        </ul>
        </el-popover>
	    <el-button 
	    v-popover:addtomypeople type = "primary" 
	    @click = "addToMyPeople()"
	    v-if="bond == 'other'" 
	    icon = " knocks-user-add-outline knocks_icon knocks_text_ms" class ="">
	    </el-button>
	    <el-button 
	    type = "danger"
	    @click = "cancelRequest()"
	    v-if="bond == 'requested'" 
	    icon = " knocks-plus-circle knocks_icon" class ="">
	    </el-button>
      <div class = "row knocks_house_keeper" v-if = "bond == 'requester' && !show_accept_shortcut" style="display:block">
      <el-button  type="danger" icon="el-icon-search">Ignore</el-button>
      <el-button-group>
        <knockselbutton 
        type = "success"
        placeholder = "Accept"
        submit_at = "request/accept"
        success_at = "done" 
        reset_on_success      
        :error_at = "[{ res : 'invalid' , msg : 'You already have this circle!' }]"
        :success_msg= " 'Added to your Circles succesfully!'"
        :scope = "['add_friend']"
        @knocks_submit_accepted = "resetContent()"
        validation_error = "There's some feilds we need you to complete it."
        connection_error = "There's a problem in your connection, please try again."
        :submit_data = "{ target : user, circles : attachedCircles }">
        </knockselbutton>
        <!-- <el-button  type="success">Accept</el-button> -->
        <el-button type="success" v-popover:circlestoaccept >
          <i class = "el-icon-arrow-down el-icon--right"></i>
        </el-button>
      </el-button-group>
    </div>
	</div>
</template>

<script>
export default {

  name: 'knocksuseractions',
  props : {
  	user : {
  		type : Number , 
  		required : true
  	} , 
  	start_as : {
  		type : Object , 
  		default : null
  	} ,
    show_accept_shortcut : {
      type : Boolean , 
      default : false ,
    },
    extras : {
    type : Object , 
    default : null,
  }
  },
  data () {
    return {
    	userObject : null , 
      userCirlces : null ,
      checkedCircles : {},

    }
  },
  mounted(){
  	if(this.start_as != null) this.userObject = this.start_as;
  	const vm = this;
    App.$on('KnocksGlogbalCirclesList' , ()=>{
      vm.userCirlces = window.UserCirclesList;
    });
  	App.$on('KnocksContentChanged' , ()=>{
      if(vm.userObject == null) return;
      if(vm.userObject != vm.user){
        if(UsersObject[vm.user] != undefined){
          vm.reboundUser(); 
        }
      }
      setTimeout(()=>{
        if(vm.userObject != vm.user){        
          if(UsersObject[vm.user] != undefined){
            vm.reboundUser(); 
          }
        }
      },300);
    })
    App.$on('knocksUserResetContent' , (id)=>{
      if(id == vm.user){
         vm.reboundUser(); 
      }
    })
  },
  computed : {
  	thatsMe(){
  		return this.userObject.id == UserId ? true : false;
  	},
    bond(){
      if( this.userObject.thatsMe ) return 'me' ;
      if(this.userObject.is_friend) return 'friend';
      if(this.userObject.requester) return 'requester';
      if(this.userObject.requested) return 'requested';
      if(!this.userObject.requested) return 'other';
    },
    attachedCircles(){
      let array , cir ;
      array = [];
      for(cir in this.checkedCircles){
        if(this.checkedCircles[cir])
          array.push(parseInt(cir));
      }
      return array;
    }
  },
  methods : {
  	addToMyPeople(){
  		const vm = this;
  		axios({
  			url : LaravelOrgin + 'request/one' , 
  			method : 'post' ,
  			data : { to : vm.user }
  		}).then((res)=>{
  			if(res == 'invalid'){
  				//Do something
  				return;
  			}else{
  			   vm.userObject.requested = true;
  		       vm.updateClientData();
  			}
  		})
  	},
  	cancelRequest(){
		const vm = this;
  		axios({
  			url : LaravelOrgin + 'request/cancel' , 
  			method : 'post' ,
  			data : { to : vm.user }
  		}).then((res)=>{
  			if(res == 'invalid'){
  				//Do something
  				return;
  			}else{
  			   vm.userObject.requested = false;
  		       vm.updateClientData();
  			}
  		})
  	},
    resetContent(){     
      window.UsersObject[this.user].is_friend = true;
      window.UsersObject[this.user].requester = window.UsersObject[this.user].requested = false ;
      App.$emit('knocksUserResetContent' , this.user);
    },
  	updateClientData(){
  		App.$emit('knocksUserDataUpdated' , {user : this.user , update : this.userObject});
  	},
  	reboundUser(){
        this.userObject = UsersObject[this.user];
    },
    updateBalloonsTimer(){
      if(this.extras != null && this.extras.hover_id != undefined){
        App.$emit('knocksStopBallonTimer' , (this.extras.hover_id));
      }else return;
    },
     runBalloonsTimer(){
      if(this.extras != null && this.extras.hover_id != undefined){
        App.$emit('knocksTurnOnBallonTimer',(this.extras.hover_id));
      }else return;
    },
 
  }
}
</script>

<style lang="css" scoped>
</style>