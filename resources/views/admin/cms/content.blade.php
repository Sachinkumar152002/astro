@push('styles')
    <style>
ul.list-group.content-autosuggest {
    position: absolute;
    width: 78%;
    z-index: 5000;
    overflow-y: auto;
    min-height: 30px;
    max-height: 130px;
    box-shadow: 0px 16px 25px;
    background: #fff;
    top: 35px;
    right: 0;
}
}
ul.list-group.content-autosuggest li { cursor: pointer;}
ul.list-group.content-autosuggest li:hover{background: #c5c5c5;}
.list-view { height: 150px; overflow: auto;}
</style>
@endpush
<div class="row" class="form-inline">
<div class="col-md-6">
<h4>Countries </h4>

<div class="input-group">
    {!! Form::label('search_country','Search Country', ['class'=>"p-1"]) !!}
    {!! Form::text('search_country', old('search_country'),['class'=>'form-control', 'autocomplete'=>"off", 'autofill'=>'off' , 'id'=>'search_country', 'v-model'=>'search.country', 'v-on:keyup'=>'searchContent("country")']) !!}
    <div class="input-group-prepend">
            <div class="input-group-text" v-on:click="blankSearch()"><i class="fa fa-times"></i></div>
          </div>
    <ul class="list-group content-autosuggest" v-if="list.country.length">
        <li class="list-group-item" v-for="(item, index) in list.country" :key="index" v-on:click="addItem('country',item)">@{{item.name}}</li>
    </ul>
</div>
<h4>Your selection</h4>

<ul class="list-group list-view ">
<li class="list-group-item" v-for="(item, index) in selection.country" :key="index">@{{index+1}}. - @{{item.name}}
    <a v-on:click="deleteItem('country',index)" href="javascript:void(0);" class="text-danger float-right"><i class="fa fa-times " ></i> </a>
</li>
</ul>


<div class="clearfix"></div>
<textarea class="hidden d-none mceNoEditor" name="contentlink[country]">@{{selection.country}}</textarea>
</div>
    </div>
@push('scripts-child')
<script>

@if(!$cms || ($cms && $cms->template!='default'))
var caaVue = new Vue({
      el: '#content_tab',
      data: {
        selection: {
            country : {!! $cms && $cms->countrylinks ? $cms->countrylinks : '[]' !!},
        },
        search: {
            country : '',
        },
        list: {
            country : [],
        },

        selectedIndex: ''
      },
      methods : {
        deleteItem(option, index) {
          this.selection[option].splice(index,1);
        },
        searchContent(option){
            let that = this;
            that.list =  {
                country : [],
            };
            if(this.search[option].length>2){
                $.post('/'+option, {_token: '{{csrf_token()}}', search:this.search[option], list: that.selection[option]}, function(response){
                    that.list[option] = response;
                });
            }
        },
        addItem(option, item){
            this.selection[option].push({ 'name':item.name, 'item_id':item.id });
            this.list =  {
                country : [],
            };
        },
        blankSearch() {
            this.list =  {
                country : [],
            };
            this.search= {
                country : '',
            };
        }
      }
    });
@endif
$(':input').attr('autocomplete','off');
</script>
@endpush
