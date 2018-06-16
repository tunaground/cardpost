insert
into	card
		(bbs_uid, title, password, open_date, refresh_date, dead, owner_only, status)
value	(:bbs_uid, :title, :password, :open_date, :refresh_date, :dead, :owner_only, :status)
