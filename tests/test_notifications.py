from familyhub import NotificationCenter


def test_notifications():
    nc = NotificationCenter()
    nc.notify("alice", "Dinner at 7")
    notes = nc.get_notifications("alice")
    assert notes == ["Dinner at 7"]
