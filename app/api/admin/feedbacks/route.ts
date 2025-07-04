import { feedbackDb } from "@/lib/database"
import { getAdminSession } from "@/lib/auth"
import { NextResponse } from "next/server"

export async function GET() {
  try {
    const isAdmin = await getAdminSession()
    if (!isAdmin) {
      return NextResponse.json({ error: "Unauthorized" }, { status: 401 })
    }

    const feedbacks = feedbackDb.getAllFeedback()
    return NextResponse.json(feedbacks)
  } catch (error) {
    return NextResponse.json({ error: "Failed to fetch feedbacks" }, { status: 500 })
  }
}
