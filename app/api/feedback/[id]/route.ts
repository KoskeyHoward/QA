import { feedbackDb } from "@/lib/database"
import { getAdminSession } from "@/lib/auth"
import { type NextRequest, NextResponse } from "next/server"

export async function GET(request: NextRequest) {
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

export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const isAdmin = await getAdminSession()
    if (!isAdmin) {
      return NextResponse.json({ error: "Unauthorized" }, { status: 401 })
    }

    const { id } = await params
    const body = await request.json()
    const { approved } = body

    if (typeof approved !== "boolean") {
      return NextResponse.json({ error: "Approved field must be a boolean" }, { status: 400 })
    }

    const result = feedbackDb.updateFeedback(Number.parseInt(id), approved)

    if (result.changes === 0) {
      return NextResponse.json({ error: "Feedback not found" }, { status: 404 })
    }

    return NextResponse.json({ message: "Feedback updated successfully" })
  } catch (error) {
    return NextResponse.json({ error: "Failed to update feedback" }, { status: 500 })
  }
}

export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const isAdmin = await getAdminSession()
    if (!isAdmin) {
      return NextResponse.json({ error: "Unauthorized" }, { status: 401 })
    }

    const { id } = await params
    const result = feedbackDb.deleteFeedback(Number.parseInt(id))

    if (result.changes === 0) {
      return NextResponse.json({ error: "Feedback not found" }, { status: 404 })
    }

    return NextResponse.json({ message: "Feedback deleted successfully" })
  } catch (error) {
    return NextResponse.json({ error: "Failed to delete feedback" }, { status: 500 })
  }
}