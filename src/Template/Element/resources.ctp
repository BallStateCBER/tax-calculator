<?php
    $references = [
        'General' => [
            'http://www.taxfoundation.org' => 'Tax Foundation'
        ],
        'Illinois' => [
            'http://www.realtor.com/illinois/nbselil.asp' => 'Find the home value in Illinois by MLS listing'
        ],
        'Indiana' => [
            'http://www.realtor.com/indiana/nbselin.asp' => 'Find the home value in Indiana by MLS listing',
            'http://www.in.gov/dlgf/4932.htm' => 'Estimate your 2010 Indiana Property Tax Bill',
            'http://profiles.cberdata.org' => 'Indiana County Profiles',
            'http://brownfield.cberdata.org/' => 'Brownfield Grant Writers\' Tool (statistical information about Indiana counties)'
        ]
    ];
?>

<div id="resources">
	<div>
		<?php foreach ($references as $category => $links): ?>
			<h3>
				<?= $category ?>
			</h3>
			<ul>
				<?php foreach ($links as $url => $title): ?>
					<li>
						<?= $this->Html->link($title, $url, ['target' => '_blank']) ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
	</div>
</div>
