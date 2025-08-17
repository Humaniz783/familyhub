from familyhub import ListManager


def test_shared_lists():
    lm = ListManager()
    lm.add_item("shopping", "milk")
    lm.add_item("wish", "bike")
    lm.add_item("todo", "clean room")
    lm.mark_done("todo", "clean room")

    assert any(item.name == "milk" for item in lm.get_list("shopping"))
    todo_items = lm.get_list("todo")
    assert todo_items[0].done is True
