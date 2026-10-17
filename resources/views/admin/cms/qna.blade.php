<h3>Questions</h3>
<ul class="list-group">
<li class="list-group-item" v-for="(item, index) in qa" :key="index">Q@{{index+1}}. - @{{item.question}}
    <a v-on:click="deleteQuestion(index)" href="javascript:void(0);" class="text-danger float-right"><i class="fa fa-trash " ></i> </a>

    <a v-on:click="editQuestion(item, index)" href="javascript:void(0);" class="text-info float-right"><i class="fa fa-edit" ></i> </a>
</li>
</ul>

<div class="form-group">
    {!! Form::label('question','Question') !!}
    {!! Form::text('question', old('question'),['class'=>'form-control', 'id'=>'question', 'v-model'=>'question']) !!}
</div>
<div class="form-group">
    {!! Form::label('answer','answer') !!}
    {!! Form::textarea('answer', old('answer'),['class'=>'form-control', 'v-model'=>'answer']) !!}
</div>
<div class="form-group">
        <button type="button" class="btn btn-info float-right" v-on:click="saveQuestion()">Save Question</button>
</div>
<div class="clearfix"></div>
<textarea class="hidden d-none mceNoEditor" name="questions">@{{qa}}</textarea>
@push('scripts-child')
<script>

@if(!$cms || ($cms && $cms->template!='default'))
var myVue = new Vue({
      el: '#qa_tab',
      data: {
        qa: {!! $cms ? $cms->questions : '[]' !!},
        question: '',
        answer: '',
        selectedIndex: ''
      },
      methods : {
        saveQuestion() {
           tinyMCE.triggerSave();
           this.answer = tinyMCE.get('answer').getContent();
           if(this.qa[this.selectedIndex]){
             this.qa[this.selectedIndex]['question'] = this.question;
             this.qa[this.selectedIndex]['answer'] = this.answer;
            }else {
              this.qa.push({question:this.question, answer:this.answer, id:''})
           }
           this.question = this.answer = this.id = '';
           this.selectedIndex = '';
           tinyMCE.get('answer').setContent('');
        },
        editQuestion(item, index){
          tinyMCE.get('answer').setContent(item.answer);
          this.answer = item.answer;
          this.question= item.question;
          this.selectedIndex = index;
        },
        deleteQuestion(index) {
          this.qa.splice(index,1);
        }
      }
    });
@endif
</script>
@endpush