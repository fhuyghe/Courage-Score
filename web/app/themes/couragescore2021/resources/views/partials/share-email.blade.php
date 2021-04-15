
<script type="text/javascript">
    actionkit.forms.contextRoot = 'https://act.couragecampaign.org/context/';
    actionkit.forms.initForm('act');
</script>

@php
    $score = App\get_score($post);
    $color = App\get_color($score);
    $letter = App\get_letter($score);

    //Check if the website got a callback from successful submission
    $thank_you_message = isset($_GET['action_id']);
@endphp

@switch($letter)
	@case('A+')
	@php $preff = get_field('score_a+', 'option') @endphp
	@break
@case('A')
	@php $preff = get_field('score_a', 'option') @endphp
	@break
@case('B')
	@php $preff = get_field('score_b', 'option') @endphp
	@break
@case('C')
	@php $preff = get_field('score_c', 'option') @endphp
	@break
@case('D')
	@php $preff = get_field('score_d', 'option') @endphp
	@break
@case('F')
	@php $preff = get_field('score_f', 'option') @endphp
	@break
@endswitch

@if(!$thank_you_message)
<div id="contact-form" class="contact-form">
	<h3 class="title"><?php _e('Contact', 'progressive');?> <?php the_title();?></h3>
	<form class="ak-form" name="act" method="POST" action="https://act.couragecampaign.org/act/" accept-charset="utf-8">
		<input type="hidden" name="page" value="{{ $post->post_name }}">
		<div class="ak-grid-row">
			<div class="ak-grid-col ak-grid-col-5-of-12">
				<div class="statement-text-wrapper ak-field-box ak-field-box-borderless">
					<div id="petition-form" class="ak-styled-fields ak-labels-overlaid ak-errs-below">
						<ul class="compact" id="ak-errors"></ul>
	<div id="unknown_user" class="ak-user-form row">
		<div id="ak-fieldbox-first_name" class="col-md-6 required">
			<input type="text" name="first_name" id="id_first_name" class="ak-userfield-input" placeholder="First name">
		</div>
		<div id="ak-fieldbox-last_name" class="required col-md-6">
			<input type="text" name="last_name" id="id_last_name" class="ak-userfield-input" placeholder="Last name">
		</div>
		<div id="ak-fieldbox-phone" class="col-md-6">
			<input type="text" name="phone" id="id_phone" class="ak-userfield-input" placeholder="Phone">
		</div>
		<div id="ak-fieldbox-user_zip" class="required col-md-6">
			<input type="text" name="user_zip" id="id_user_zip" class="ak-userfield-input" placeholder="Zip">
		</div>
		<div id="ak-fieldbox-email" class="required col-md-12">
					<input type="text" name="email" id="id_email" class="ak-userfield-input" placeholder="Email address">
		</div>
		<div id="ak-fieldbox-user_custom_message" class="col-md-12">
			<textarea name="action_rating_message" id="id_action_rating_message" class="ak-userfield-input ak-has-overlay"><?php echo $preff;?></textarea>
		</div>
		<input type="hidden" name="country" value="United States">
	</div>
						<div class="ak-field">
							<textarea id="id_comment" name="action_comment" placeholder="Add your own message..."></textarea>
						</div>
						<button type="submit" class="ak-submit-button">Send</button>
						<p><?php _e('NOTE: Although you may be disappointed with your representative, please be respectful. Use this opportunity to offer constructive feedback. Please abstain from negative, disparaging language, including, but not limited to: expletives, comments about race, gender identity, sexual orientation, ethnicity or religion, and anything specific to appearance.', 'progressive');?></p>
					</div>
				</div>  
			</div>
		</div><!--gridrow-->
	</form>
	
	@else

	<h3 class="title"><?php _e('Thank you!', 'progressive');?></h3>
	<div class="p-single__form">
		<div class="ak-form">
			<div id="ak-fieldbox-user_custom_message" class="col-xs-12">
				<div class="text" style="display: block; padding: 20px;"><?php _e('Thank you for sharing your thoughts with your legislator. Close this window to return to <a href="http://www.couragescore.org">CourageScore.org', 'progressive');?></div>
			</div>
		</div>

	</div>
	@endif
</div>