<template>
    <div class="events-detail-wrapper">

                <!-- #MODAL FILE ADD -->
        <div class="modal fade" :id="modalId">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        
                    </div>
                    <div class="modal-body">
					       <div class="my_alert2"></div>
                        <div class='modal-wraper'>
                        <img id="preview" src="" />
                       </div>
				

							  
							  
                                    <button @click="uploadFile" class="btn btn-default send_foto" :disabled="loading">
                                        <span v-show="loading">Сохранение..</span>
                                        <span v-show="!loading">Сохранить</span>
                                    </button>
                               
                          
							
                     
                    </div>
                </div>
            </div>
        </div>

 
      
      
                                  
                                
        <div class="tabs-left">
            <ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
                <li class="active">
                    <a href="#tab-r1" data-toggle="tab">Общее</a>
                </li>
             
                
                <li>
                    <a href="#tab-r4" data-toggle="tab">Аватар</a>
                </li>
                
            </ul>
            <div class="tab-content">

                <!-- #MAIN -->
                <div class="tab-pane active" id="tab-r1">
				<div class="my_alert"></div>
                    <fieldset>
                        <div class="form-horizontal">
               
							<div class="form-group">
                                <label class="col-md-2 control-label">Имя:</label>
								 <div class="col-md-6">
                                    <input v-model="name"
									maxlength="50" 
									disabled=""
									class="form-control" 
									placeholder="Введите имя">
                                </div>
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Фамилия:</label>
								 <div class="col-md-6">
                                    <input v-model="last_name" 
									maxlength="50" 
									disabled=""
									class="form-control" 
									placeholder="Введите Фамилию">
                                </div>
							</div>
							<div class="form-group">
							
                                <label class="col-md-2 control-label">E-Mail:*:</label>
								 <div class="col-md-6">
                                    <input v-model="email" 
									maxlength="50" 
									disabled=""
									class="form-control" 
									placeholder="Введите email">
                                </div>
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Мобильный:</label>
								 <div class="col-md-6">
                                    <input v-model="phone"
                                     maxlength="255" 	
                                     disabled=""									 
									class="form-control" 
									placeholder="Введите мобильный">
                                </div>
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Дата рождения (DD.MM.YYYY):</label>
								 <div class="col-md-6">
                                    <input v-model="birthday" 
									maxlength="50" 
									disabled=""
									class="form-control" 
									placeholder="Дату рождения">
                                </div>
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Страна:</label>
								 <div class="col-md-6">
                                    <input v-model="country.name" 
									maxlength="255" 
									disabled=""
									class="form-control" placeholder="Введите Страну">
                                </div>
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Населенный пункт:</label>
								 <div class="col-md-6">
                                    <input v-model="city" 
									class="form-control" 
									maxlength="255" 
									disabled=""
									placeholder="Введите Населенный пункт">
                                </div>
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Новый пароль:</label>
								 <div class="col-md-6">
                                    <input type="password" 
									v-model="password" 
									maxlength="50" 
									autocomplete="off"
									class="form-control" placeholder="Введите пароль">

                                </div>
								
							</div>
							<div class="form-group">
                                <label class="col-md-2 control-label">Подтверждение нового пароля:</label>
								 <div class="col-md-6">
                                    <input type="password" 
									v-model="password_confirmation" 
									maxlength="50" 
									autocomplete="off"
									class="form-control" placeholder="Введите подтверждение пароля">
                                </div>
							</div>
							
							<input type="hidden" v-model="vkladka1" 
                        </div>
                    </fieldset>
					<!-- #ACTIONS -->
            <div class="text-center">
                <br>
                <br>
                <button class="btn btn-success" @click="save">Сохранить</button>
                
            </div>
			
                </div>

                

                <!-- #FILES -->
                <div class="tab-pane" id="tab-r4">
                    <!-- hidden crop params -->
                    <input type="hidden" id="x1" name="x1" />
                    <input type="hidden" id="y1" name="y1" />
                    <input type="hidden" id="x2" name="x2" />
                    <input type="hidden" id="y2" name="y2" />
					<input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />
					<input type="hidden" id="n_width" name="n_width" />
                    <input type="hidden" id="n_height" name="n_height" />
					<input type="hidden" id="width" name="width" />
               
					<div class="my_alert2"></div>

                    <h2 style="margin-bottom:5px">Пожалуйста, выберите файл изображения</h2>
					<p style="margin-top:5px" class="help-block">
                      <i>Допустимые файлы: jpg, png, размером не более 20мб</i></p
					  <br>
                    <div><input type="file" 
					name="image_file" 
					id="image_file" 
					@change="onFileChange"
					
					 />
					</div>

                    <div class="error"></div>
                 </div>

                <!-- #ACTIVITY -->
                
            </div>

            <div class="clb"></div>

            
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewsDetail",
        props: {
            item: Object,
			country:Object,
        },
        data () {
            return {
                modalId: 'modal-grid-' + Math.floor(Math.random() * 1000000),
                dialog: '',
				vkladka1:'vkladka1',
                errors: '',
                loading: false,
                name:'',
				last_name:'',
				EMAIL:'',
				phone:'',
				city:'',
				birthday:'',
				PERSONAL_CITY:'',
				password:'',
				password_confirmation:'',
				password_error:'',
			    imageSrc: '',
				image: null,
				flag:'',
				nameImg:''
                
            }
        },
        mounted () {
			  $('body').on('click','.my_closse',function(){
				 
				  var display = $(this).css('display');
				  if(display == 'block'){
					  $(this).parent().css({'display':'none'})
				  }else{
					 $(this).parent().css({'display':'block'})
				  }
				  
			  })
			 
            document.addEventListener('DOMContentLoaded', this.init);
        },
   computed: {
      tr() {
      
      }
    },
        methods: {
            init: function () {
				
                let th = this;
                th.id           = th.item.id;
                th.name        = th.item.name || '';
                th.last_name     = th.item.last_name || '';
                th.email      = th.item.email || '';
				th.birthday  = th.item.birthday || '';
				th.phone = th.item.phone;
                th.city   = th.item.city  || '';
                th.country   = this.country;

				
                th.dialog = $('#'+this.modalId);
                th.dialog.on('hidden.bs.modal', function () {
                    th.refresh();
                });

                //
                
            },
			scrop(){
				
			},
	   onFileChange (e) {
		   $('.okno').addClass('okno2');
			var screen1 = screen.width;
		    if(screen1 > 1000){
			screen1 = 800;
			}
			if(screen1 < 600){
			
			var screen2 = screen1;//ширина модального окна
            }else{
			var screen2 = screen1;//ширина модального окна
			}
			
	   let formData = new FormData();
	   var file = document.querySelector('#image_file');
	   //var n_width = $('#n_width').val();

		formData.append('files', file.files[0]);
		formData.append('n_width', screen2);
		axios.post('profilesing',formData, {headers: {'Content-Type': 'multipart/form-data','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
                    .then((response) => {
						console.log(response.data);
						console.log(response.data.url);
						$('.okno').removeClass('okno2');
						if(response.data.url){
							
						 var split = response.data.url.split('/');
						 var name_img = split[split.length-1];
						 this.nameImg = name_img;
						 $('#n_width').val(response.data.width);
						 $('#n_height').val(response.data.height);
						 $('#preview').attr('src',response.data.url);

						}
					
						//alert(response.data);
						
						if(response.data.html){
						if (response.data.html == 'errors') {
							
							$('.my_alert2').html(response.data.view);
							return false;
					   }
						}
						
					    updateInfo2();
					    fileSelectHandler();
						this.loading = false;
				        this.dialog.modal('show');
                        
		
					})
		
            },
	showModal:function(){
		 $('.my_alert').html();
		this.dialog.modal('show');
	},


            refresh: function () {
                this.errors = '';
                document.querySelector('#new-file').value = null;
            },
            save: function (params, callback) {
				
			    let th = this;
                let formData = new FormData();
                this.loading = true;
                formData.append('vkladka1', this.vkladka1);

                formData.append('password', this.password);
				formData.append('password_confirmation', this.password_confirmation);
				
			 axios.post('/profile/'+this.id, formData, {headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
                  .then((response) => {

					  console.log(response.data);
					 //console.log(response.data.html);//
					 this.loading = false;
					
					  $('.my_alert').html('');

					 if (response.data.html =='success') {
						 $('.my_alert').html(response.data.view);
						 return false;
					  }
					 if (response.data.html =='errors') {
					  $('.my_alert').html(response.data.view);
					  return false;
                              
                      } 
                     }).catch((error) => {
							//console.log(error);
							//this.errors = error;
                            //th.loading = false;
                            //console.error(error);
                        });
						
				
				
            },
            update: function (callback) {
                let th = this,
                    formData = new FormData(),
                    announcePic = document.querySelector('#announce_pic'),
                    detailPic = document.querySelector('#detail_pic');

                th.loading = true;

                formData.append('title', th.title);
                formData.append('announce', th.announceEditor.getData());
                formData.append('detail', th.detailEditor.getData());
                formData.append('is_active', th.is_active);
                // formData.append('started_at', th.started_at);
                // formData.append('expired_at', th.expired_at);

                if (announcePic.files.length > 0) {
                    formData.append('announce_pic', announcePic.files[0]);
                }

                if (detailPic.files.length > 0) {
                    formData.append('detail_pic', detailPic.files[0]);
                }

                axios.post('/admin/oss/news/'+th.id, formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then((response) => {
                        th.loading      = false;
                        th.title        = response.data.title;
                        th.announce     = response.data.announce;
                        th.detail       = response.data.detail;
                        th.is_active    = response.data.is_active;
                        th.started_at   = response.data.started_at;
                        th.expired_at   = response.data.expired_at;

                        CKEDITOR.instances['ckeditor-announce'].setData(th.announce);
                        CKEDITOR.instances['ckeditor-detail'].setData(th.detail);

                        if (callback) {
                            callback();
                        } else {
                            location.href = '/admin/oss/news';
                        }

                    }).catch((error) => {
                        th.errors = error;
                        th.loading = false;
                    })
            },
            attachFile: function () {
                this.dialog.modal('show');
            },
            removeFile: function (index) {
                this._removeFile(index);
            },
            uploadFile: function () {
                if (this.id > 0) {
					
                    this._uploadFile();
                } else {
                    this.save({}, this._uploadFile);
                }
            },
            _removeFile: function (index) {
                let th = this;
                th.loading = true;

                if (confirm('Удалить?')) {

                    axios.delete('/admin/oss/news/'+th.id+'/files/'+th.list[index].id)
                        .then((response) => {
                            th.loading = false;
                            if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                                th.errors = response.data.errors;
                            } else {
                                th.list = response.data;
                                th.refresh();
                            }
                        })
                        .catch((error) => {
                            th.loading = false;
                            console.error(error);
                        })
                }
            },
            _uploadFile: function () {
				console.log('вызван метод:_uploadFile start');
				
				var x1 = $('#x1').val();
				var y1 = $('#y1').val();
				var x2 = $('#x2').val();
				var y2 = $('#y2').val();
				var w = $('#w').val();
				var h = $('#h').val();
				var name = this.nameImg;
				
				var file = document.querySelector('#image_file');
				this.loading = true;
				
            
					
				  let formData = new FormData();
				  //formData.append('files', file.files[0]);
				  
				  formData.append('x1', x1);
                  formData.append('y1', y1);
				  formData.append('x2', x2);
				  formData.append('y2', y2);
				  formData.append('w', w);
				  formData.append('h', h);
				  formData.append('name', name);
				  axios.post('/profile/'+this.id, formData, {headers: {'Content-Type': 'multipart/form-data','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
                    .then((response) => {
						console.log('метод uploadFiles');
					console.log(response.data);
						if(response.data.html){
						if (response.data.html == 'errors') {
							this.dialog.modal('hide');
							this.loading = false;
							$('.my_alert2').html(response.data.view);
							return false;}}
						if (response.data.html =='success') {
							this.dialog.modal('hide');
							$('.login-info img').attr('src',response.data.url);
							this.loading = false;
							$('.my_alert2').html(response.data.view);
						  //location.href = '/profile';
					   }

                    

                    }).catch((error) => {
                        th.errors = error;
                        th.loading = false;
                    })
					 
                    
                   
                
            },
        }
    }
</script>

<style scoped>

.modal-dialog{
height:1000px;
max-width:100%;
text-align:center;
}
.modal-content{

}
.modal-body{
	
}
img#preview{
	
}
</style>
