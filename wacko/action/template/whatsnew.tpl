[ === main === ]
	[ ' message ' ]
	[= mark =
		<small><a href="[ ' href ' ]">[ ' _t: MarkRead ' ]</a></small>
	=]
	[= xml =
		<span class="desc_rss_feed">
			<a href="[ ' href ' ]">
				<img src="[ ' db: theme_url ' ]icon/spacer.png" title="[ ' _t: ChangesXMLTip ' ]" alt="XML" class="btn-feed">
			</a>
		</span>
		<br><br>
	=]
	[= nopages _ =
		[ ' _t: NoPagesFound ' ]
	=]
	[''' pagination ''']
	<ul class="ul_list">
		[= page _ =
			<li><strong>[ ' day ' ]</strong>
				<ul>
					[= l _ =
						<li class="lined[ ' viewed ' ]">
							<span class="dt">[ ' revisions ' ]</span> &mdash; [ ' page ' ]
							<img src="[ ' db: theme_url ' ]icon/spacer.png" title="[ ' title ' ]" alt="[ ' alt ' ]" class="[ ' class ' ]">
							[ ' link ' ]
								[= to =
									[ ' _t: To ' ]&nbsp;&nbsp;[ ' link ' ]
								=]
							<span title="[ ' _t: Cluster ' ]">&rarr; [ ' cluster ' ]</span>
							 . . . . . . . . . . . . . . . . 
							<small>
								[ ' user ' ]
								[= review =
									<span class="review">[ ' href ' ]</span>
								=]
								[= edit =
									<span class="editnote">[[ ' note ' ]]</span>
								=]
							</small>
						</li>
					=]
				</ul>
			<br></li>
		=]
	</ul>

	[''' pagination ''']


[= pagination =]
<nav class="pagination">[ ' text ' ]</nav>
