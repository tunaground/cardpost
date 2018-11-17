select	c2.card_uid,
		c2.bbs_uid,
		c2.title,
		c2.password,
		c2.open_date,
		c2.refresh_date,
		c2.owner_only,
		c2.dead,
		c2.status,
		p.name,
		c1.size
from   (select	card.card_uid,
				count(post.post_uid) size
		from	card,
				post
		where	1 = 1
			and card.bbs_uid = :bbs_uid
			and post.card_uid = card.card_uid
		group by card.card_uid
		order by card.refresh_date desc
		limit	:start_from, :limit_count) c1,
		card c2,
		post p
where	1 = 1
	and c2.card_uid = c1.card_uid
	and	p.card_uid = c2.card_uid
	and p.post_order = 0
order by c2.refresh_date desc
