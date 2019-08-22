<?php
/**
 * Settings page template
 */
?>
<div id="kava-settings-page">
	<div class="kava-settings-page">
		<h1 class="cs-vui-title"><?php esc_html_e( 'Kava Theme Settings', 'kava' ); ?></h1>
		<div class="cx-vui-panel">
			<cx-vui-tabs
				:in-panel="false"
				value="layout-settings"
				layout="vertical">

				<cx-vui-tabs-panel
					name="layout-settings"
					label="<?php _e( 'Layout settings', 'kava' ); ?>"
					key="layout-settings">

					<div class="kava-settings-page__title-wrap">
						<div class="cx-vui-subtitle"><?php esc_html_e( 'Disable Container of Content on Archive Pages', 'kava' ); ?></div>
						<div class="cx-vui-component__desc"><?php esc_html_e( 'List of CPT that will be a disabled container of content', 'kava' ); ?></div>
					</div>

					<div class="kava-settings-page__group-controls">
						<div
							class="kava-settings-page__group-control"
							v-for="(option, index) in pageOptions.disable_content_container_archive_cpt.options">
							<cx-vui-switcher
								:key="index"
								:name="`disable_content_container_archive_cpt-${option.value}`"
								:label="option.label"
								:wrapper-css="[ 'equalwidth' ]"
								return-true="true"
								return-false="false"
								v-model="pageOptions.disable_content_container_archive_cpt.value[option.value]"
							>
							</cx-vui-switcher>
						</div>
					</div>

					<div class="kava-settings-page__title-wrap">
						<div class="cx-vui-subtitle"><?php esc_html_e( 'Disable Container of Content on Singular Pages', 'kava' ); ?></div>
						<div class="cx-vui-component__desc"><?php esc_html_e( 'List of CPT that will be a disabled container of content', 'kava' ); ?></div>
					</div>

					<div class="kava-settings-page__group-controls">
						<div
							class="kava-settings-page__group-control"
							v-for="(option, index) in pageOptions.disable_content_container_single_cpt.options">
							<cx-vui-switcher
								:key="index"
								:name="`disable_content_container_single_cpt-${option.value}`"
								:label="option.label"
								:wrapper-css="[ 'equalwidth' ]"
								return-true="true"
								return-false="false"
								v-model="pageOptions.disable_content_container_single_cpt.value[option.value]"
							>
							</cx-vui-switcher>
						</div>
					</div>

					<cx-vui-select
						name="single_post_template"
						label="<?php esc_html_e( 'Default Single Post Template', 'kava' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:options-list="pageOptions.single_post_template.options"
						v-model="pageOptions.single_post_template.value">
					</cx-vui-select>

				</cx-vui-tabs-panel>
			</cx-vui-tabs>
		</div>
	</div>
</div>
