from familyhub import Messaging


def test_messaging():
    msg = Messaging()
    msg.send_message("alice", "bob", "Hello")
    inbox = msg.get_inbox("bob")
    assert len(inbox) == 1
    assert inbox[0].content == "Hello"
