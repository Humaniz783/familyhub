"""In-memory message passing between family members."""
from __future__ import annotations

from dataclasses import dataclass
from typing import Dict, List


@dataclass
class Message:
    sender: str
    recipient: str
    content: str


class Messaging:
    def __init__(self) -> None:
        self.mailboxes: Dict[str, List[Message]] = {}

    def send_message(self, sender: str, recipient: str, content: str) -> None:
        self.mailboxes.setdefault(recipient, []).append(
            Message(sender=sender, recipient=recipient, content=content)
        )

    def get_inbox(self, user: str) -> List[Message]:
        return list(self.mailboxes.get(user, []))
