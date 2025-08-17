"""Manage shared lists such as shopping, wishes and tasks."""
from __future__ import annotations

from dataclasses import dataclass
from typing import Dict, List


@dataclass
class ListItem:
    name: str
    quantity: int = 1
    done: bool = False


class ListManager:
    """Keep track of multiple shared lists."""

    def __init__(self) -> None:
        self.lists: Dict[str, List[ListItem]] = {
            "shopping": [],
            "wish": [],
            "todo": [],
        }

    def add_item(self, list_name: str, name: str, quantity: int = 1) -> None:
        self._ensure_list(list_name)
        self.lists[list_name].append(ListItem(name=name, quantity=quantity))

    def mark_done(self, list_name: str, name: str) -> None:
        self._ensure_list(list_name)
        for item in self.lists[list_name]:
            if item.name == name:
                item.done = True
                break

    def get_list(self, list_name: str) -> List[ListItem]:
        self._ensure_list(list_name)
        return list(self.lists[list_name])

    def _ensure_list(self, list_name: str) -> None:
        self.lists.setdefault(list_name, [])
