# Human Made Content Moderation

This plugin adds settings at network level which allow for network wide content moderation to be enforced. When enforced, the disallowed keys set at network level will be used instead of the ones on each site thereby centralising management of disallowed keys.

For sites using GravityWiz's Gravity Forms gp-blocklist plugin to moderate content submitted via forms, this plugin extends that functionality to allow for network wide moderation of form submissions.
This removes the repetitive work of enabling moderation on a per form basis since by default that gp-blocklist plugin moderates content on a per form basis meaning each form on a site needs to have moderation enabled individually and this repeated for all sites that are to have their form submission content moderated.

For future, this plugin may even be extended to perform the moderation logic that gp-blocklist plugin does which would then remove the need of that third-party plugin on sites that want to moderate Gravity Forms form submissions.

## Installation

The plugin can be installed using Composer as `humanmade/content-moderation`
