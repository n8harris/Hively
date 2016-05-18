
App.views.PageQuestions = alloy.View.extend({

	className: 'questions-container',
	events: {
		"submit form": "onSubmitForm"
	},

	_initialize: function(options) {
		this.questions = null;
		this.question = null;
		this.chosenOption = null;
		this.questionIndex = 1;
		this.left_value = null;
		this.right_value = null;
		this.sliderPoints = 0;
		this.questionsCount = 0;
		this.sliderConstant = 100;
		this.questionsContainer = '.questions-container';
		this.progressContainer = '#globalFooter';
		this.isBusiness = false;
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_questions;
		$(this.el).html($(tmpl).render());
		if(App.Data.getInstance().user.get('role') == 'business'){
			this.isBusiness = true;
		}

		alloy.Api.getInstance().request({
			api: 'question',
			call: 'get',
			data: {
				business: this.isBusiness
			},
			success: this.onQuestions,
			error: this.onQuestionsError
		});
	},

	onQuestions: function(response) {

		if(response.status > 0) {
			this.questions = response.data.questions;
			this.questionsCount = this.questions.length;
			this.question = this.questions.pop();
			this.injectQuestion(this.question);
		} else {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}

	},
	onSubmitForm: function(event) {
		event.preventDefault();
		var $form = $(event.target);
		var formValues = $form.serializeArray();

		if(this.validateForm()) {
			if (!this.question.multiple) {
				var lastQuestion = false;
				if($('.slider-range').prop("min") != '0' || $('.slider-range').prop("max") != '200') {
					bootbox.alert("Unable to submit question");
				} else {
					if (formValues[0].value < this.sliderConstant) {
						this.sliderPoints = this.sliderConstant - formValues[0].value;
						this.chosenOption = this.getByIdValue(this.question.options, this.left_value);
					} else {
						this.sliderPoints = formValues[0].value - this.sliderConstant;
						this.chosenOption = this.getByIdValue(this.question.options, this.right_value);
					}
					if(this.questions.length == 0){
						lastQuestion = true;
					}
					alloy.Api.getInstance().request({
						api: 'response',
						call: 'create',
						data: {
							question_title: this.question.title,
							points: this.sliderPoints,
							categories: this.chosenOption.categories,
							last_question: lastQuestion
						},
						success: this.onCreateResponseSuccess,
						error: this.onCreateResponseError
					});
				}
			}
		}
	},
	onCreateResponseSuccess: function(response) {
		if(response.status > 0) {
			if (this.questions.length == 0) {
				alloy.Api.getInstance().request({
					api: 'total',
					call: 'create',
					data: {},
					success: this.onCreateTotalSuccess,
					error: this.onCreateTotalError
				});
			} else {
				this.question = this.questions.pop();
				this.injectQuestion(this.question);
			}
		} else {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}
	},
	onCreateResponseError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
		bootbox.alert("Unable to submit response");
	},
	onCreateTotalSuccess: function(response) {
		if(response.status > 0) {
				App.Router.getInstance().navigate('profile', {trigger: true});
		} else {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}
	},
	onCreateTotalError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
		bootbox.alert("Unable to create question aggregation");
	},
	onQuestionsError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
	},
	validateForm: function() {
		return true;
	},
	injectQuestion: function(question) {
		$(App.main_container).hide();
		$(App.loader).show();
		if(!this.question.multiple) {
			this.left_value = question.options[0].id;
			this.right_value = question.options[1].id;
			$(this.questionsContainer, this.el).html($(App.tmpl.tmpl_partial_slider).render({
				title: question.title,
				left_option: question.options[0],
				right_option: question.options[1]
			}));
			$(this.progressContainer).html($(App.tmpl.tmpl_questions_footer_control).render({
				question_count: this.questionsCount,
				question_index: this.questionsCount - this.questions.length - 1
			}));
			App.vent.trigger('rendered');
		}
	},
	getByIdValue: function(arr, value) {
	  for (var i=0, iLen=arr.length; i<iLen; i++) {

	    if (arr[i].id == value) return arr[i];
	  }
	},
	arrayMergeContrib: function(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i].id === a[j].id) {
								a[i].contribution += a[j].contribution;
                a.splice(j--, 1);
						}
        }
    }

    return a;
}
});
