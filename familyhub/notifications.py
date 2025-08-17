"""Basic notification center."""
from __future__ import annotations

from typing import Dict, List


class NotificationCenter:
    def __init__(self) -> None:
        self.notifications: Dict[str, List[str]] = {}

    def notify(self, user: str, message: str) -> None:
        self.notifications.setdefault(user, []).append(message)

    def get_notifications(self, user: str) -> List[str]:
        return list(self.notifications.get(user, []))
