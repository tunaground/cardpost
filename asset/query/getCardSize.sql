select	count(post.post_uid) size
from	card,
		post
where	1 = 1
	and card.card_uid = :card_uid
	and post.card_uid = card.card_uid
group by card.card_uid
