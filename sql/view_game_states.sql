CREATE VIEW games_human AS
SELECT	game.id_game,
		game.name,
		game.completion,
        game.affection,
		picture.picture_path,
        cs.label AS cartridge_state,
        ms.label AS manual_state,
        ps.label AS packing_state
        
	FROM `game`
LEFT JOIN picture ON game.picture = picture.id_picture
LEFT JOIN state as cs ON game.cartridge_state = cs.id_state
LEFT JOIN state as ms ON game.manual_state = ms.id_state
LEFT JOIN state as ps ON game.packing_state = ps.id_state